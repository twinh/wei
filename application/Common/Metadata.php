<?php
/**
 * Metadata
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Common
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-28 17:26:04
 */

/**
 * @see Qwin_Application_Metadata
 */
require_once 'Qwin/Application/Metadata.php';

class Common_Metadata extends Qwin_Application_Metadata
{
    /**
     * 当前元数据对应的应用目录结果
     * @var array
     */
    protected $_asc;

    /**
     * 需要进行链接转换的行为
     * @var array
     */
    protected $_linkAction = array('list', 'view');

    /**
     * @var array           获取记录的配置
     *
     *  -- type             记录类型
     *
     *  -- name             记录名称
     */
    protected $_recordOption = array(
        'type'          => array(),
        'alias'         => array(),
        //'exceptAlias'   => array(),
    );

    /**
     * 初始化常用变量
     */
    public function  __construct()
    {
        parent::__construct();
        $this->url = Qwin::call('-url');
    }

    public static function getRecordByAsc($asc)
    {
        $meta       = self::getByAsc($asc);
        $record     = Common_Model::getByAsc($asc);
        return $meta->getReord($record);
    }

    /**
     * 获取元数据对应的记录对象
     *
     * @param Doctrine_Record $record 原始Doctrine记录对象
     * @param array $option 配置选项
     * @return Doctrine_Record 带字段定义,表定理,关联关系的Doctrine记录对象
     */
    public function getRecord(Doctrine_Record $record = null, array $option = array())
    {
        $option = $option + $this->_recordOption;
        $option['type'] = (array)$option['type'];
        $option['alias'] = (array)$option['alias'];
        
        if (null === $record) {
            $record = Common_Model::getByAsc($this->getAsc());
        }
        
        // 将元数据加入记录配置中
        $this->toRecord($record);

        foreach ($this['model'] as $alias => $model) {
            if (in_array($model['type'], $option['type']) || in_array($alias, $option['alias'])) {
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
    public static function getQueryByAsc($asc, array $option = array())
    {
        $meta       = self::getByAsc($asc);
        $record     = Common_Model::getByAsc($asc);
        return $meta->getQuery($record, $option);
    }

    /**
     * 获取元数据对应的查询对象
     *
     * @param Doctrine_Record $record 原始Doctrine记录对象
     * @param array $option 配置选项
     * @return Doctrine_Query 查询对象
     * @todo padb问题
     */
    public function getQuery(Doctrine_Record $record = null, array $option = array())
    {
        $option = $option + $this->_recordOption;
        $option['type'] = (array)$option['type'];
        $option['alias'] = (array)$option['alias'];

        if (null === $record) {
            $recordClass = Common_Model::getByAsc($this->getAsc(), false);
            $record = Qwin::call($recordClass);
        } else {
            $recordClass = get_class($record);
        }

        // 将元数据加入记录配置中
        $this->toRecord($record);

        // 初始化查询
        // TODO 更多选项,如缓存,索引
        $query = Doctrine_Query::create()->from($recordClass);

        // 增加默认查询
        if (!empty($this['db']['defaultWhere'])) {
            $this->addWhereToQuery($query, $this['db']['defaultWhere']);
        }

        foreach ($this['model'] as $alias => $model) {
            if (in_array($model['type'], $option['type']) || in_array($alias, $option['alias'])) {
                $this->setRecordRelation($record, $model);
                $query->leftJoin($recordClass . '.' . $alias . ' ' . $alias);
            }
        }

        return $query;
    }

    /**
     * 设置模型间的关联关系
     *
     * @param Doctrine_Record $record 记录对象
     * @param array $model 关联配置
     * @return Common_Metadata 当前对象
     */
    public function setRecordRelation(Doctrine_Record $record, array $model)
    {
        $modelObject = Common_Model::getByAsc($model['asc']);
        $name = get_class($modelObject);

        $metaObject = self::getByAsc($model['asc']);
        $metaObject->toRecord($modelObject);

        // 设置模型关系
        call_user_func(
            array($record, $model['relation']),
            $name . ' as ' . $model['alias'],
            array(
                'local' => $model['local'],
                'foreign' => $model['foreign']
            )
        );
        return $this;
    }

    /**
     * 将元数据的域定义,数据表定义加入模型中
     *
     * @param Doctrine_Record $model Doctrine对象
     * @return Common_Metadata 当前对象
     */
    public function toRecord(Doctrine_Record $record)
    {
        // 设置数据表
        $record->setTableName($this['db']['table']);

        // 设置字段
        $fieldList = $this['field']->getAttrList(array('isDbField', 'isDbQuery'));
        foreach ($fieldList as $field) {
            $record->hasColumn($field);
        }

        // 重新初始化记录,否则Doctrine将提示属性或关联组件不存在
        // TODO 是否有更合适的方法
        $record->__construct();

        return $this;
    }

    /**
     * 为Doctrine查询对象增加排序语句
     *
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return Common_Metadata 当前对象
     * @todo 关联元数据的排序
     */
    public function addOrderToQuery(Doctrine_Query $query, array $addition = null)
    {
        $order = null != $addition ? $addition : $this['db']['order'];

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        // 数据表字段的域
        $queryField = $this['field']->getAttrList('isDbQuery');
        $orderType = array('DESC', 'ASC');

        foreach ($order as $fieldSet) {
            // 不被允许的域名称
            if (!in_array($fieldSet[0], $queryField)) {
                continue;
            }
            $fieldSet[1] = strtoupper($fieldSet[1]);
            if (!in_array($fieldSet[1], $orderType)) {
                $fieldSet[1] = $orderType[0];
            }
            $query->addOrderBy($alias . $fieldSet[0] . ' ' .  $fieldSet[1]);
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句
     *
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return Common_Metadata 当前对象
     * @todo 完善查询类型
     * @todo 复杂查询
     */
    public function addWhereToQuery(Doctrine_Query $query, array $addition = null)
    {
        $search = null != $addition ? $addition : $this['db']['where'];

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        // 数据表字段的域
        $queryField = $this['field']->getAttrList('isDbQuery');
        // TODO　是否使用%s替换
        $searchType = array(
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

        foreach ($search as $fieldSet) {
            // 不被允许的域名称
            if (!in_array($fieldSet[0], $queryField)) {
                continue;
            }
            if (!isset($fieldSet[2])) {
                $fieldSet[2] = key($searchType);
            } else {
                $fieldSet[2] = strtolower($fieldSet[2]);
                !isset($searchType[$fieldSet[2]]) && $fieldSet[2] = key($searchType);
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
            if(null === $value) {
                if ('eq' == $fieldSet[2]) {
                    $query->andWhere($alias . $fieldSet[0] . ' IS NULL');
                    continue;
                } elseif ('ne' == $fieldSet[2]) {
                    $query->andWhere($alias . $fieldSet[0] . ' IS NOT NULL');
                    continue;
                }
            }
            $query->andWhere($alias . $fieldSet[0] . ' ' . $searchType[$fieldSet[2]] . ' ' . $valueSign, $value);
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
     * @return Common_Metadata 当前对象
     */
    public function addOffsetToQuery(Doctrine_Query $query, $addition = null)
    {
        $offset = 0;
        if (null != $addition) {
            $addition = intval($addition);
            if (0 < $addition) {
                $offset = $addition;
            }
        }
        $query->offset($offset);
        return $this;
    }

    /**
     * 为Doctrine查询对象增加限制语句
     *
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的限制配置
     * @return Common_Metadata 当前对象
     */
    public function addLimitToQuery(Doctrine_Query $query, $addition = null)
    {
        $limit = 0;
        if (null != $addition) {
            $addition = intval($addition);
            if (0 < $addition) {
                $limit = $addition;
            }
        }
        $query->limit($limit);
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查询语句
     *
     * @param Doctrine_Query $query
     * @return Common_Metadata 当前对象
     * @todo 是否要将主类加入到$meta['model']数组中,减少代码重复
     */
    public function addSelectToQuery(Doctrine_Query $query)
    {
        /**
         * 设置主类的查询语句
         */
        // 调整主键的属性,因为查询时至少需要选择一列
        $primaryKey = $this['db']['primaryKey'];
        $this['field']
             //->setAttr($primaryKey, 'isList', true)
             ->setAttr($primaryKey, 'isDbField', true)
             ->setAttr($primaryKey, 'isDbQuery', true);

        $queryField = $this['field']->getAttrList(array('isDbQuery', 'isDbField'));
        $query->select(implode(', ', $queryField));

        /**
         * 设置关联类的查询语句
         */
        foreach ($this['model'] as $model) {
            $linkedMetaObj = self::getByAsc($model['asc']);

            // 调整主键的属性,因为查询时至少需要选择一列
            $primaryKey = $linkedMetaObj['db']['primaryKey'];
            $linkedMetaObj->field
                          ->setAttr($primaryKey, 'isDbField', true)
                          ->setAttr($primaryKey, 'isDbQuery', true);

            $queryField = $linkedMetaObj->field->getAttrList(array('isDbQuery', 'isDbField'));
            foreach ($queryField as $field) {
                $query->addSelect($model['alias'] . '.' . $field);
            }
        }
        return $this;
    }

    /**
     * 根据类名获取应用结构配置
     *
     * @return array 配置
     */
    public function getAsc()
    {
        if (!empty($this->_asc)) {
            return $this->_asc;
        }

        $class = get_class($this);
        $parts = explode('_', get_class($this));
        if (4 == count($parts) && 'Metadata' == $parts[2]) {
            return $this->_asc = array(
                'package' => $parts[0],
                'module' => $parts[1],
                'controller' => $parts[3],
            );
        }
        require_once 'Qwin/Metadata/Exception.php';
        throw new Qwin_Metadata_Exception('Class "' . $class . '" do not have a valid Application Structure Configuration.');
    }

    /**
     * 设置元数据各个细节,包括域,分组,关联模型等等,每个元数据类应该包含该方法
     */
    public function setMetadata()
    {
        return null;
    }

    /**
     * 设置基本的元数据,包括编号,创建时间,修改时间和操作.
     *
     * @return Common_Metadata 当前对象
     */
    public function setCommonMetadata()
    {
        return $this
            ->setIdMetadata()
            ->setCreatedData()
            ->setModifiedData()
            ->setOperationMetadata();
    }

    /**
     * 设置高级的元数据,包括编号,创建时间,修改时间,分配者,是否删除,操作
     *
     * @return Common_Metadata 当前对象
     */
    public function setAdvancedMetadata()
    {
        return $this
            ->setIdMetadata()
            ->setCreatedData()
            ->setModifiedData()
            ->setAssignToMetadata()
            ->setIsDeletedMetadata()
            ->setOperationMetadata();
    }

    /**
     * 设置编号域的元数据配置,编号是最为常见的域
     *
     * @return obejct 当前对象
     */
    public function setIdMetadata()
    {
        $this->merge(array(
            'id' => array(
                'basic' => array(
                    'order' => -1,
                    'layout' => 2,
                ),
                'form' => array(
                    '_type' => 'hidden',
                    /*'_type' => 'text',
                    '_widgetDetail' => array(
                        array(
                            array('Qwin_Widget_JQuery_CustomValue', 'render'),
                        ),
                    ),*/
                    'name' => 'id',
                ),
                'list' => array(
                    'hide' => true,
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                    'isReadonly' => 0,
                ),
            ),
        ), 'field');
        return $this;
    }

    /**
     * 设置创建域,包括创建人和创建时间
     *
     * @return Common_Metadata 当前对象
     */
    public function setCreatedData()
    {
        $this->merge(array(
            'created_by' => array(
                'basic' => array(
                    'order' => 1020,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'db' => array(
                    'type' => 'date',
                ),
                'attr' => array(
                    'isReadonly' => 1,
                ),
            ),
            'date_created' => array(
                'basic' => array(
                    'order' => 1060,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'db' => array(
                    'type' => 'date',
                ),
                'attr' => array(
                    'isReadonly' => 1,
                ),
            ),
        ), 'field');
        return $this;
    }

    /**
     * 设置修改域,包括修改人和修改时间
     *
     * @return Common_Metadata 当前对象
     */
    public function setModifiedData()
    {
        $this->merge(array(
            'modified_by' => array(
                'basic' => array(
                    'order' => 1040,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
            ),
            'date_modified' => array(
                'basic' => array(
                    'order' => 1080,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isList' => 1,
                ),
            ),
        ), 'field');
        return $this;
    }

    /**
     * 设置操作域的元数据配置,操作域主要用于列表
     *
     * @return obejct 当前对象
     */
    public function setOperationMetadata()
    {
        $this->merge(array(
            'operation' => array(
                'basic' => array(
                    'order' => 1100,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isDbField' => 0,
                    'isDbQuery' => 0,
                    'isView' => 0,
                    'isList' => 1,
                ),
            ),
        ), 'field');
        return $this;
    }

    public function setAssignToMetadata()
    {
        $this->merge(array(
            'assign_to' => array(
                'basic' => array(
                    'order' => 1100,
                ),
                'form' => array(
                    '_type' => 'text',
                    '_widgetDetail' => array(
                        array(
                            array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                            'LBL_MODULE_MEMBER',
                            array(
                                'package' => 'Common',
                                'module' => 'Member',
                                'controller' => 'Member',
                                'list' => 'id,group_id,username,email',
                            ),
                            array(
                                'username',
                                'id'
                            ),
                        ),
                    ),
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isList' => 0,
                ),
                'validator' => array(
                    'rule' => array(
                        'required' => true,
                    ),
                ),
            ),
        ), 'field');
        return $this;
    }

    public function setIsDeletedMetadata()
    {
        $this->merge(array(
            'is_deleted' => array(
                'basic' => array(
                    'order' => 1105,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isList' => 0,
                    'isView' => 0,
                    'isReadonly' => 1,
                ),
            ),
        ), 'field');
        return $this;
    }

     /**
     * 在列表操作下,设置记录添加时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function sanitiseListDateCreated($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * 在列表操作下,设置记录修改时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function sanitiseListDateModified($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * 在列表操作下,为操作域设置按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     * @todo 简化,重利用,是否需要用微件的形式
     */
    public function sanitiseListOperation($value, $name, $data, $dataCopy)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $asc = $this->getAsc();
        if (!isset($this->controller)) {
            // TODO　不重复加载
            $this->controller = Common_Controller::getByAsc($asc);
            $this->forbiddenAction = $this->controller->getForbiddenAction();
        }
        // 不为禁用的行为设置链接
        $operation = array();
        if (!in_array('edit', $this->forbiddenAction)) {
            $operation['edit'] = array(
                'url'   => $url->url($asc, array('action' => 'Edit', $primaryKey => $dataCopy[$primaryKey])),
                'title' => $lang->t('ACT_EDIT'),
                'icon'  => 'ui-icon-tag',
            );
        }
        if (!in_array('view', $this->forbiddenAction)) {
            $operation['view'] = array(
                'url'   => $url->url($asc, array('action' => 'View', $primaryKey => $dataCopy[$primaryKey])),
                'title' => $lang->t('ACT_VIEW'),
                'icon'  => 'ui-icon-lightbulb',
            );
        }
        /*if (!in_array('add', $this->forbiddenAction)) {
            $operation['add'] = array(
                'url'   => $url->url($asc, array('action' => 'Add', $primaryKey => $dataCopy[$primaryKey])),
                'title' => $lang->t('ACT_COPY'),
                'icon'  => 'ui-icon-transferthick-e-w',
            );
        }*/
        if (!in_array('delete', $this->forbiddenAction)) {
            if (!isset($this->page['useTrash'])) {
                $icon = 'ui-icon-close';
                $jsLang = 'MSG_CONFIRM_TO_DELETE';
            } else {
                $icon = 'ui-icon-trash';
                $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
            }
            $operation['delete'] = array(
                'url'   => 'javascript:if(confirm(QWIN_PATH.Lang.' . $jsLang . ')){window.location=\'' . $url->url($asc, array('action' => 'Delete', $primaryKey => $dataCopy[$primaryKey])) . '\';}',
                'title' => $lang->t('ACT_DELETE'),
                'icon'  => $icon,
            );

        }
        if (5 != func_num_args()) {
            $data = '';
            foreach ($operation as $row) {
                $data .= Qwin_Util_JQuery::icon($row['url'], $row['title'], $row['icon']);
            }
            return $data;
        } else {
            return $operation;
        }
    }

    /**
     * 在列表操作下,初始化排序域的值,依次按5递增
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return int 当前域的新值
     */
    public function sanitiseAddOrder($value, $name, $data, $dataCopy)
    {
        return 50;
        $query = $this->getQuery($this);
        $result = $query
            ->select($this->db['primaryKey'] . ', order')
            ->orderBy('order DESC')
            ->fetchOne();
        if(false != $result)
        {
            return $result['order'] + 5;
        }
        return 0;
    }

    /**
     * 在入库操作下,转换编号
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbId($value, $name, $data, $dataCopy)
    {
        if (null == $value) {
            $value = Qwin_Util_String::uuid();
        }
        return $value;
    }

    /**
     * 在入库操作下,转换创建时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbDateCreated($value, $name, $data, $dataCopy)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 在入库操作下,转换修改时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbDateModified()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 在入库操作下,转换分类的值
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseDbCategoryId($value)
    {
        '0' == $value && $value = null;
        return $value;
    }

    public function sanitiseDbCreatedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function sanitiseDbModifiedBy($value, $name, $data, $dataCopy)
    {
        $member = Qwin::call('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function sanitiseDbIsDeleted($value, $name, $data, $dataCopy)
    {
        return 0;
    }

    public function sanitiseEditAssignTo($value, $name, $data, $dataCopy)
    {
        Crm_Helper::sanitisePopupMember($value, $name, 'username', $this);
        return $value;
    }

    public function setIsLink($value, $name, $data, $dataCopy, $action)
    {
        //if (in_array($action, $this->_linkAction)) {
            $asc = $this->getAsc();
            !isset($this->url) && $this->url = Qwin::call('-url');
            $name = str_replace(':', '\:', $name);
            $dataCopy[$name] = str_replace(':', '\:', $dataCopy[$name]);
            $value = '<a href="' . $this->url->url($asc, array('action' => 'Index', 'search' => $name . ':' . $dataCopy[$name])) . '">' . $data[$name] . '</a>';
        //}
        return $value;
    }
}
