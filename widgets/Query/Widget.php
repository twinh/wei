<?php

/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-07-05 01:18:32
 */

require_once 'Doctrine/Query.php';

class Query_Widget extends Doctrine_Query implements Qwin_Widget_Interface
{
    /**
     * 数据排序类型
     * @var array
     */
    protected static $_orderTypes = array(
        'DESC', 'ASC'
    );
    
    /**
     * 数据查询类型
     * @var array
     * @todo 是否使用%s替换 
     */
    protected static $_searchTypes = array(
        'eq' => '=',
        'ne' => '<>',
        'lt' => '<',
        'le' => '<=',
        'gt' => '>',
        'ge' => '>=',
        'bw' => 'LIKE',
        'bn' => 'NOT LINK',
        'in' => 'IN',
        'ni' => 'NOT IN',
        'ew' => 'LIKE',
        'en' => 'NOT LIKE',
        'cn' => 'LIKE',
        'nc' => 'NOT LIKE',
    );
    
    /**
     * 查询对应的数据库元数据
     * @var Qwin_Meta_Db
     */
    protected $_meta;
    
    /**
     * 查询对应的记录对象
     * @var Doctrine_Record
     */
    protected $_record;
    
    /**
     * 根据模块获取查询对象
     * 
     * @param string|Qwin_Module $module
     * @param string $name 数据库元数据的键名
     * @return Query_Widget 查询对象
     */
    public static function getByModule($module, $name = 'db')
    {
        $meta = Meta_Widget::getByModule($module);
        return self::getByMeta($meta[$name]);
    }
    
    /**
     * 获取数据库元数据对应的查询对象
     * 
     * @param Qwin_Meta_Db $meta
     * @return Query_Widget 查询对象
     */
    public static function getByMeta(Qwin_Meta_Db $meta)
    {
        $parentMeta = $meta->getParent();
        
        // 获取记录对象
        $recordClass = Model_Widget::getByModule($parentMeta['module'], false);
        $record = Qwin::call($recordClass);
        
        // 将数据库配置加入记录配置中
        self::metaToRecord($meta, $record);
        
        // 根据别名和索引构建DQL的from语句
        $from = $recordClass;
        if ($meta['alias']) {
            $from .= ' ' . $meta['alias'];
        }
        if ($meta['indexBy']) {
            $from .= ' INDEXBY ' . $meta['indexBy'];
        }

        // 初始化查询
        // TODO 更多选项,如缓存
        $query = Doctrine_Query::create(null, __CLASS__)->from($from);
        
        // 将元数据存储在查询对象中,方便获取其关联关系
        $query->_meta = $meta;
        $query->_record = $record;

        // 增加默认查询
        if (!empty($this['where'])) {
            $query->addRawWhere($this['where']);
        }

        return $query;
    }
    
    public function leftJoinByType($type)
    {
        $types = is_array($type) ? $type : array($type);
        foreach ($this->_meta['relations'] as $alias => $relation) {
            if (in_array($relation['type'], $types)) {
                $this->setRelation($relation);
                $this->leftJoin($this->getRootAlias() . '.' . $alias . ' ' . $alias);
            }
        }
        return $this;
    }
    
    public function leftJoinByAlias($name)
    {
        !is_array($name) && $name = array($name);
        foreach ($name as $alias) {
            if (!isset($this->_meta['relations'][$alias])) {
                throw new Qwin_Widget_Exception('Undefined relation "' . $alias . '"');
            }
            $this->setRelation($this->_meta['relations'][$alias]);
            $this->leftJoin($this->getRootAlias() . '.' . $alias . ' ' . $alias);
        }
        return $this;
    }
    
    public function setRelation($relation)
    {
        // 初始化记录和元数据
        $record = Model_Widget::getByModule($relation['module']);
        $meta = Meta_Widget::getByModule($relation['module'])->get($relation['meta']);

        self::metaToRecord($meta, $record);

        // 设置模型关系
        call_user_func(
            array($this->_record, $relation['relation']),
            get_class($record) . ' as ' . $relation['alias'],
            array(
                'local' => $relation['local'],
                'foreign' => $relation['foreign']
            )
        );
        
        return $this;
    }
    
    public function leftJoinByName($name)
    {
        return $this->leftJoinByAlias($name);
    }
    
    /**
     * 将数据库元数据的域定义,数据表定义加入模型中
     * 
     * @param Qwin_Meta_Db $meta 数据库元数据对象
     * @param Doctrine_Record $record Doctrine记录对象
     */
    public static function metaToRecord(Qwin_Meta_Db $meta, Doctrine_Record $record)
    {
        // 设置数据表
        $record->setTableName($meta['table']);

        // 设置字段
        foreach ($meta['fields'] as $name => $field) {
            if ($field['dbField'] && $field['dbQuery']) {
                $record->hasColumn($name);
            }
        }
        
        // 重新初始化记录,否则Doctrine将提示属性或关联组件不存在
        // TODO 是否有更合适的方法
        $record->__construct();
    }
    
    public static function getRecordByModule()
    {
        
    }
    
    public static function getRecordByMeta()
    {
        
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
     * 设置模型间的关联关系
     *
     * @param Doctrine_Record $record 记录对象
     * @param array $meta2 关联配置
     * @return Com_Meta 当前对象
     */
    public function setRecordRelation(Doctrine_Record $record, array $meta2)
    {
        $meta2Object = Com_Model::getByModule($meta2['module']);
        $name = get_class($meta2Object);

        $metaObject = self::getByModule($meta2['module']);
        $metaObject->toRecord($meta2Object);

        // 设置模型关系
        call_user_func(
            array($record, $meta2['relation']),
            $name . ' as ' . $meta2['alias'],
            array(
                'local' => $meta2['local'],
                'foreign' => $meta2['foreign']
            )
        );
        return $this;
    }

    /**
     * 为Doctrine查询对象增加排序语句
     *
     * @param Doctrine_Query $query
     * @param array|null $order 排序配置
     * @return Com_Meta 当前对象
     * @todo 关联元数据的排序
     */
    public function addRawOrder(Qwin_Meta_Db $db, $order = null)
    {
        if (empty($order)) {
            return $this;
        }
        
        // 排序为字符串形式,进行分割
        if (is_string($order)) {
            $order = Qwin_Util_String::splitQuery($order);
        }

        // 排序只有一项,补全数组
        if (isset($order[0]) && is_string($order[0])) {
            $order = array($order);
        }
        
        $alias = $this->getRootAlias();
        $alias && $alias .= '.';

        foreach ($order as $field) {
            if (!isset($field[0]) || !isset($db['fields'][$field[0]]) || !$db['fields'][$field[0]]['dbField']) {
                continue;
            }
            $field[1] = strtoupper($field[1]);
            if (!in_array($field[1], self::$_orderTypes)) {
                $field[1] = $orderType[0];
            }
            $this->addOrderBy($alias . $field[0] . ' ' .  $field[1]);
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句
     *
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return Com_Meta 当前对象
     * @todo 完善查询类型
     * @todo 复杂查询
     */
    public function addRawWhere(Qwin_Meta_Db $db, $search = null)
    {
        if (is_string($search)) {
            $search = Qwin_Util_String::splitQuery($search);
        }
        $search = is_null($search) ? $db['where'] : $search;

        $alias = $this->getRootAlias();
        '' != $alias && $alias .= '.';

        foreach ($search as $fieldSet) {
            if (!isset($db['fields'][$fieldSet[0]]) || !$db['fields'][$fieldSet[0]]['dbField']) {
                continue;
            }
            if (!isset($fieldSet[2])) {
                $fieldSet[2] = 'eq';
            } else {
                $fieldSet[2] = strtolower($fieldSet[2]);
                !isset(self::$_searchTypes[$fieldSet[2]]) && $fieldSet[2] = 'eq';
            }
            switch ($fieldSet[2]) {
                case 'bw':
                case 'bn':
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]);
                    break;
                case 'ew':
                case 'en':
                    $value = $this->_escapeWildcard($fieldSet[1]) . '%';
                    break;
                case 'cn':
                case 'nc':
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]) . '%';
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]) . '%';
                    break;
                /*case 'in':
                case 'ni':
                    $value = is_array($fieldSet[1]) ? $fieldSet[1] : array($fieldSet[1]);
                    break;
                /*case 'eq':
                case 'ne':
                case 'lt':
                case 'le':
                case 'gt':
                case 'ge':*/
                default:
                    $value = $fieldSet[1];
                    break;
            }
            if ('in' == $fieldSet[2] || 'ni' == $fieldSet[2]) {
                $valueSign = '(?)';
            } else {
                $valueSign = '?';
            }

            // null and not null
            if(null === $value || 'NULL' === $value) {
                if ('eq' == $fieldSet[2]) {
                    $this->andWhere($alias . $fieldSet[0] . ' IS NULL');
                    continue;
                } elseif ('ne' == $fieldSet[2]) {
                    $this->andWhere($alias . $fieldSet[0] . ' IS NOT NULL');
                    continue;
                }
            }
            $this->andWhere($alias . $fieldSet[0] . ' ' . self::$_searchTypes[$fieldSet[2]] . ' ' . $valueSign, $value);
        }
        return $this;
    }

    /**
     * 转义LIKE语言中的通配符%和_
     *
     * @param string $value
     * @return string
     * @todo 其他通配符[]
     * @todo 其他数据库是否支持
     */
    protected function _escapeWildcard($value)
    {
        return strtr($value, array('%' => '\%', '_' => '\_'));
    }

    /**
     * 为Doctrine查询对象增加偏移语句
     *
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的偏移配置
     * @return Com_Meta 当前对象
     */
    public function addRawOffset(Qwin_Meta_Db $db, $offset)
    {
        $offset = intval($offset);
        $offset = $offset < 0 ? 0 : $offset;
        $this->offset($offset);
        return $this;
    }

    /**
     * 为Doctrine查询对象增加限制语句
     *
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的限制配置
     * @return Com_Meta 当前对象
     */
    public function addRawLimit(Qwin_Meta_Db $db, $addition = null)
    {
        $limit = 0;
        if (null != $addition) {
            $addition = intval($addition);
            if (0 < $addition) {
                $limit = $addition;
            }
        }
        $this->limit($limit);
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查询语句
     *
     * @param Doctrine_Query $query
     * @return Com_Meta 当前对象
     * @todo 是否要将主类加入到$meta['model']数组中,减少代码重复
     */
    public function addRawSelect(Qwin_Meta_Db $db)
    {
        /**
         * 设置主类的查询语句
         */
        foreach ($db['fields'] as $name => $field) {
            if ($field['dbQuery'] && $field['dbField']) {
                $this->addSelect($name);
            }
        }
        
        /**
         * 设置关联类的查询语句
         */
        //foreach ((array)$this->offsetLoad('meta') as $meta2) {
//            $linkedMetaObj = self::getByModule($meta2['module']);
//
//            // 调整主键的属性,因为查询时至少需要选择一列
//            $id = $linkedMetaObj['db']['id'];
//            $linkedMetaObj->fields
//                          ->setAttr($id, 'isDbField', true)
//                          ->setAttr($id, 'isDbQuery', true);
//
//            $queryField = $linkedMetaObj->fields->getAttrList(array('isDbQuery', 'isDbField'));
//            foreach ($queryField as $field) {
//                $query->addSelect($meta2['alias'] . '.' . $field);
//            }
        //}
        return $this;
    }
}