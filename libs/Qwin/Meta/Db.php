<?php
/**
 * Db
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Qwin
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-27 18:13:16
 */

class Qwin_Meta_Db extends Qwin_Meta_Common
{
    /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    /**
     * @var array $_defaults        默认选项
     *
     *      -- name                 名称
     *
     *      -- title                标题标识, 默认为 FLD_$fieldUppeName
     *
     *      -- description          域描述
     *
     *      -- order                排序
     *
     *      -- dbField              是否为数据库字段
     *
     *      -- dbQuery              是否允许数据库查询
     *
     *      -- urlQuery             是否允许Url查询
     *
     *      -- readonly             是否只读
     */
    protected $_fieldDefaults = array(
        'name' => null,
        'title' => null,
        'description' => array(),
        'dbField' => true,
        'dbQuery' => true,
        'urlQuery' => true,
        'readonly' => false,
//        'db' => array(
//            'type' => 'string',
//            'length' => null,
//        ),
    );

    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'fields' => array(),
        //'type' => 'sql',
        'table' => null,
        'id' => 'id',
        'offset' => 0,
        'limit' => 10,
        'order' => array(),
        'where' => array(),
    );

    /**
     * @var array           获取记录的配置
     *
     *  -- type             记录类型
     *
     *  -- name             记录名称
     */
    protected $_recordOptions = array(
        'type'          => array(),
        'alias'         => array(),
        //'exceptAlias'   => array(),
    );

    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $option 选项
     * @return Qwin_Meta_Field 当前对象
     */
    public function merge($data, array $options = array())
    {
        $data = (array)$data + $this->_defaults;
        !is_array($data['fields']) && (array)$data['fields'];

        // 处理通配选项
        if (array_key_exists('*', $data['fields'])) {
            $this->_fieldDefaults = $this->_fieldDefaults + (array)$data['fields']['*'];
            unset($data['fields']['*']);
        }

        foreach ($data['fields'] as $name => &$field) {
            !isset($field['name']) && $field['name'] = $name;
            //!isset($field['title']) && $field['title'] = 'FLD_' . strtoupper($name);
            $field = (array)$field + $this->_fieldDefaults;
        }
        $this->exchangeArray($data);
        return $this;
    }

    /**
     *
     * @param Doctrine_Record $record 原始Doctrine记录对象
     * @param array|string $type 类型数字或字符串,如view,db
     * @return Doctrine_Query 查询对象
     */
    public function getQueryByType(Doctrine_Record $record = null, $type = array())
    {
        !is_array($type) && $type = (array)$type;
        return $this->getQuery($record, array(
            'type' => $type,
        ));
    }

    /**
     * 获取元数据对应的查询对象
     *
     * @param Doctrine_Record $record 原始Doctrine记录对象
     * @param array $options 选项
     * @return Doctrine_Query 查询对象
     * @todo padb问题
     */
    public function getQuery(Doctrine_Record $record = null, array $options = array())
    {
        $options = $options + $this->_recordOptions;
        $options['type'] = (array)$options['type'];
        $options['alias'] = (array)$options['alias'];
        $meta = $this->getParent();

        if (is_null($record)) {
            // 尝试通过父元数据的模型配置加载记录对象
            // TODO 一个模块,多个记录对象如何处理
            $recordClass = Model_Widget::getByModule($meta['module']->getUrl(), false);
            $record = Qwin::call($recordClass);
        } else {
            $recordClass = get_class($record);
        }

        // 将数据库配置加入记录配置中
        $this->toRecord($record);

        // 初始化查询
        // TODO 更多选项,如缓存,索引
        $query = Doctrine_Query::create(null, 'Qwin_Meta_Query')->from($recordClass);

        // 增加默认查询
        if (!empty($this['where'])) {
            $query->addRawWhere($this['where']);
        }

        foreach ((array)$meta->offsetLoad('meta') as $alias => $meta2) {
            if (in_array($meta2['type'], $options['type']) || in_array($alias, $options['alias'])) {
                $relatedRecord = Com_Model::getByModule($meta2['module']);
                $this->setRecordRelation($record, $meta2);
                $query->leftJoin($recordClass . '.' . $alias . ' ' . $alias);
            }
        }

        return $query;
    }

    /**
     * 将元数据的域定义,数据表定义加入模型中
     *
     * @param Doctrine_Record $model Doctrine对象
     * @return Com_Meta 当前对象
     */
    public function toRecord(Doctrine_Record $record)
    {
        // 设置数据表
        $record->setTableName($this['table']);

        // 设置字段
        foreach ($this['fields'] as $name => $field) {
            if ($field['dbField'] && $field['dbQuery']) {
                $record->hasColumn($name);
            }
        }

        // 重新初始化记录,否则Doctrine将提示属性或关联组件不存在
        // TODO 是否有更合适的方法
        $record->__construct();

        return $this;
    }

    public static function getRecordByModule($module)
    {
        $meta = self::getByModule($module);
        $record = Com_Model::getByModule($module);
        return $meta->getReord($record);
    }

    /**
     * 获取元数据对应的记录对象
     *
     * @param Doctrine_Record $record 原始Doctrine记录对象
     * @param array $options 选项
     * @return Doctrine_Record 带字段定义,表定理,关联关系的Doctrine记录对象
     */
    public function getRecord(Doctrine_Record $record = null, array $options = array())
    {
        $options = $options + $this->_recordOptions;
        $options['type'] = (array)$options['type'];
        $options['alias'] = (array)$options['alias'];

        if (null === $record) {
            $record = Com_Model::getByModule($this->getModule());
        }

        // 将元数据加入记录配置中
        $this->toRecord($record);

        foreach ($this['model'] as $alias => $model) {
            if (in_array($model['type'], $options['type']) || in_array($alias, $options['alias'])) {
                $this->setRecordRelation($record, $model);
            }
        }

        return $record;
    }

    /**
     * 根据应用结构配置,获取Doctrine的查询对象
     *
     * @param array $asc 应用结构配置
     * @param array $type 类型,可选
     * @param array $name 名称,可选
     * @return Doctrine_Query 查询对象
     */
    public static function getQueryByModule($module, array $options = array())
    {
        $meta = self::getByModule($module);
        $record = Com_Model::getByModule($module);
        return $meta->getQuery($record, $options);
    }
}
