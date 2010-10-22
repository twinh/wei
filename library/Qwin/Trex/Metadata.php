<?php
/**
 * Metadata
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
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Trex_Metadata extends Qwin_Metadata
{
    /**
     * 数据表前缀
     * @var string
     */
    public $tablePrefix;

    public function toDoctrine(Qwin_Metadata $meta, Doctrine_Record $model)
    {
        $model->setTableName($meta->tablePrefix . $meta->db['table']);
        $queryField = $meta->field->getAttrList('isDbQuery');
        foreach($meta->field as $field)
        {
            $model->hasColumn($field['form']['name']);
        }
    }

    /**
     * 获取数据表前缀,方便调用
     *
     * @return string 表前缀
     */
    public function getTablePrefix($adapter = null)
    {
        if(null == $this->tablePrefix)
        {
            $config = Qwin::run('-ini')->getConfig();
            $mainAdapter = $config['database']['mainAdapter'];
            $this->tablePrefix = $config['database']['adapter'][$mainAdapter]['prefix'];
        }
        return $this->tablePrefix;
    }

    /**
     * 获取标准的类名
     *
     * @param string $addition 附加的字符串
     * @param array $set 配置数组
     * @return string 类名
     */
    public function getClassName($addition, $set)
    {
        // TODO !!
        !isset($set['namespace']) && $set['namespace'] = 'Trex';
        return $set['namespace'] . '_' . $set['module'] . '_' . $addition . '_' . $set['controller'];
    }

    /**
     * 获取Doctrine的查询对象
     *
     * @param array $set 配置
     * @param boolen $isJoinModel 是否连接关联的模块
     * @return object Doctrine_Record 查询对象
     * @todo 缓存$query
     */
    public function getDoctrineQuery($set, $isJoinModel = true)
    {
        /**
         * 初始元数据和模型主类
         */
        $manager = Doctrine_Manager::getInstance();
        $metaName = $this->getClassName('Metadata', $set);
        $metaObj = Qwin_Metadata_Manager::get($metaName);

        // TODO 表前缀等..
        if('padb' == $metaObj['db']['type'])
        {
            $manager->setCurrentConnection('padb');
            $tablePrefix = '';
        } else {
            $tablePrefix = $this->getTablePrefix();
        }

        $queryField = $metaObj->field->getAttrList(array('isDbField', 'isDbQuery'));
        $modelName = $this->getClassName('Model', $set);
        $modelObj = Qwin::run($modelName);
        $modelObj->setTableName($tablePrefix . $metaObj['db']['table']);
        foreach($queryField as $field)
        {
            $modelObj->hasColumn($field);
        }

        /**
         * 初始化Doctrine查询
         */
        $connObject = null;
        $queryClass = null;
        if('padb' == $metaObj['db']['type'])
        {
            $connObject = $manager->getConnection('padb');
            $queryClass = 'Doctrine_Query_Padb';
        }

        $query = Doctrine_Query::create($connObject, $queryClass)->from($modelName);

        /**
         * 加载其他关联的类
         */
        if($isJoinModel)
        {
            foreach($metaObj['model'] as $model)
            {
                // 不连接数据关联的模型
                if('relatedDb' == $model['type'])
                {
                    continue;
                }
                Qwin::load($model['metadata']);
                $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);
                $queryField = $linkedMetaObj->field->getAttrList(array('isDbField', 'isDbQuery'));

                $linkedModelObj = Qwin::run($model['name']);
                $linkedModelObj->setTableName($this->getTablePrefix() . $linkedMetaObj['db']['table']);
                foreach($queryField as $field)
                {
                    $linkedModelObj->hasColumn($field);
                }

                // 设置模型关系
                call_user_func(
                    array($modelObj, 'hasOne'),
                    $model['name'] . ' as ' . $model['alias'],
                        array(
                            'local' => $model['local'],
                            'foreign' => $model['foreign']
                        )
                );
                $query->leftJoin($modelName . '.' . $model['alias'] . ' ' . $model['alias']);
            }
        }
        return $query;
    }

    /**
     * 将主元数据关联的元数据加入到主元数据中
     *
     * @param Qwin_Metadata $meta 主元数据
     */
    public function connectMetadata(Qwin_Metadata $meta)
    {
        $mainMetaField = clone $meta['field'];
        foreach($meta['model'] as $model)
        {
            // 不连接显示型模型
            if('db' != $model['type'])
            {
                continue;
            }
            Qwin::load($model['metadata']);
            $relatedMeta = Qwin_Metadata_Manager::get($model['metadata']);
            $tmpMeta = array();
            foreach($relatedMeta['field'] as $field)
            {
                // 存储原来的名称
                $field['form']['_oldName'] = $field['form']['name'];
                $field['form']['_arrayName'] = $model['alias'] . '[' . $field['form']['name'] . ']';
                $field['form']['name'] = $model['alias'] . '_' . $field['form']['name'];
                if($field['form']['_oldName'] == $field['form']['id'])
                {
                    $field['form']['id'] = str_replace('_', '-', $field['form']['name']);
                }
                $tmpMeta[$field['form']['name']] = $field;
            }
            $mainMetaField->addData($tmpMeta);
        }
        return $mainMetaField;
    }

    /**
     * 计算两个数组的交集,键名来自第一个数组,值来自第二个数组
     *
     * @param array $array1 第一个参数数组
     * @param array $array2
     * @return array
     */
    public function intersect($array1, $array2)
    {
        foreach($array1 as $key)
        {
            if(isset($array2[$key]))
            {
                $array1[$key] = $array2[$key];
            } else {
                $array1[$key] = null;
            }
        }
        return $array1;
    }

    /**
     * 为Doctrine查询对象增加查询语句
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @return object 当前对象
     * @todo 是否要将主类加入到$meta['model']数组中,减少代码重复
     */
    public function addSelectToQuery(Qwin_Metadata $meta, Doctrine_Query $query)
    {
        /**
         * 设置主类的查询语句
         */
        // 调整主键的属性,因为查询时至少需要选择一列
        $primaryKey = $meta['db']['primaryKey'];
        $meta->field
             //->setAttr($primaryKey, 'isList', true)
             ->setAttr($primaryKey, 'isDbField', true)
             ->setAttr($primaryKey, 'isDbQuery', true);
        
        $queryField = $meta->field->getAttrList(array('isDbQuery', 'isDbField'));
        $query->select(implode(', ', $queryField));

        /**
         * 设置关联类的查询语句
         */
        foreach($meta['model'] as $model)
        {
            Qwin::load($model['metadata']);
            $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);

            // 调整主键的属性,因为查询时至少需要选择一列
            $primaryKey = $linkedMetaObj['db']['primaryKey'];
            $linkedMetaObj->field
                          ->setAttr($primaryKey, 'isDbField', true)
                          ->setAttr($primaryKey, 'isDbQuery', true);
            
            $queryField = $linkedMetaObj->field->getAttrList(array('isDbQuery', 'isDbField'));
            foreach($queryField as $field)
            {
                $query->addSelect($model['alias'] . '.' . $field);
            }
        }
        return $this;
    }


    /**
     * 获取Url中的排序配置,对字段域不做验证,字段域的验证应该是交给服务执行的.
     *
     * @param string $fieldName 字段域的名称
     * @param string $typeName 排序的名称
     * @return array 标准元数据的排序配置
     */
    public function getUrlOrder($fieldName = 'orderField', $typeName = 'orderType')
    {
        $request = Qwin::run('Qwin_Request');

        $orderField = $request->g('orderField');

        // 地址未设置排序
        if(null == $orderField)
        {
            return array();
        }

        $orderType = strtoupper($request->g('orderType'));
        $typeOption = array('DESC', 'ASC');
        if(!in_array($orderType, $typeOption))
        {
            $orderType = $typeOption[0];
        }

        return array(
            array($orderField, $orderType),
        );
    }

    /**
     * 获取Url中的查找配置,对字段域和操作符不做验证
     *
     * @param string $fieldName 字段域的名称
     * @param string $valueName 搜索值的名称(searchString)
     * @param string $operName 操作符的名称
     * @return array 标准元数据的搜索配置
     * @todo 高级复杂搜索配置
     */
    public function getUrlWhere($fieldName = 'searchField', $valueName = 'searchValue', $operName = 'searchOper')
    {
        $request = Qwin::run('Qwin_Request');

        $searchField = $request->g($fieldName);
        if(null == $searchField)
        {
            return array();
        }

        $searchValue = $request->g($valueName);
        $searchOper  = $request->g($operName);
        return array(
            array($searchField, $searchValue, $searchOper),
        );
    }
    
    /**
     * 获取Url中的偏移配置
     *
     * @param string $limitName 字段域的名称,应该与getUrlOffset中的rowName一致
     * @return int 标准元数据的限制配置
     * @todo 最大值允许配置
     */
    public function getUrlLimit($limitName = 'rowNum')
    {
        $request = Qwin::run('Qwin_Request');
        $limit = $request->g($limitName);
        500 < $limit && $limit = 500;

        return $limit;
    }

    /**
     * 获取Url中的限制配置
     *
     * @param string $limitName 字段域的名称,应该与getUrlOffset中的rowName一致
     * @return int 标准元数据的限制配置
     * @todo 最大值允许配置
     */
    public function getUrlOffset($pageName = 'page', $limitName = 'rowNum')
    {
        $request = Qwin::run('Qwin_Request');
        $page = $request->g($pageName);
        $limit = $request->g($limitName);
        500 < $limit && $limit = 500;
        $offset = ($page - 1) * $limit;

        return $offset;
    }

    /**
     * 获取Url中的显示域的配置
     *
     * @param string $listName 键名
     * @param string $delimiter 分隔符
     * @return array
     */
    public function getUrlListField($listName = 'listName', $delimiter = ',')
    {
        $request = Qwin::run('Qwin_Request');
        $list = $request->g($listName);
        if(null != $list)
        {
            $list = explode($delimiter, $list);
            foreach($list as $key => $value)
            {
                $pos = strpos($value, '.');
                if(false !== $pos)
                {
                    $list[$key] = array(
                        substr($value, 0, $pos),
                        substr($value, $pos + 1),
                    );
                }
            }
        }
        return $list;
    }

    /**
     * 获取Url中元数据主键的值
     *
     * @param array $set 应用结构配置
     * @return null|string 值
     */
    public function getUrlPrimaryKeyValue(array $set)
    {
        $request = Qwin::run('Qwin_Request');
        $primaryKey = $this->getPrimaryKeyName($set);
        return $request->g($primaryKey);
    }

    public function getPrimaryKeyName(array $set)
    {
        $ini = Qwin::run('-ini');
        $metadataName = $ini->getClassName('Metadata', $set);
        if(!class_exists($metadataName))
        {
            return null;
        }
        $meta = Qwin_Metadata_Manager::get($metadataName);
        return $meta['db']['primaryKey'];
    }

    public function getMetadataBySet(array $set)
    {
        $ini = Qwin::run('-ini');
        $metadataName = $ini->getClassName('Metadata', $set);
        if(class_exists($metadataName))
        {
            $meta = Qwin_Metadata_Manager::get($metadataName);
        } else {
            $metadataName = 'Trex_Metadata';
            $meta = Qwin::run($metadataName);
        }
        Qwin::addMap('-meta', $metadataName);
        return $meta;
    }

    /**
     * 为Doctrine查询对象增加排序语句
     * 
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return object 当前对象
     * @todo 关联元数据的排序
     */
    public function addOrderToQuery(Qwin_Metadata $meta, Doctrine_Query $query, array $addition = null)
    {
        $order = null != $addition ? $addition : $meta['db']['order'];

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        // 数据表字段的域
        $queryField = $meta['field']->getAttrList('isDbQuery');
        $orderType = array('DESC', 'ASC');

        foreach($order as $fieldSet)
        {
            // 不被允许的域名称
            if(!in_array($fieldSet[0], $queryField))
            {
                continue;
            }
            $fieldSet[1] = strtoupper($fieldSet[1]);
            if(!in_array($fieldSet[1], $orderType))
            {
                $fieldSet[1] = $orderType[0];
            }
            $query->addOrderBy($alias . $fieldSet[0] . ' ' .  $fieldSet[1]);
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return object 当前对象
     * @todo 完善查询类型
     * @todo 复杂查询
     */
    public function addWhereToQuery(Qwin_Metadata $meta, Doctrine_Query $query, array $addition = null)
    {
        $search = null != $addition ? $addition : $meta['db']['where'];

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        // 数据表字段的域
        $queryField = $meta['field']->getAttrList('isDbQuery');
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
        
        foreach($search as $fieldSet)
        {
            // 不被允许的域名称
            if(!in_array($fieldSet[0], $queryField))
            {
                continue;
            }
            if(!isset($fieldSet[2]))
            {
                $fieldSet[2] = key($searchType);
            } else {
                $fieldSet[2] = strtolower($fieldSet[2]);
                !isset($searchType[$fieldSet[2]]) && $fieldSet[2] = key($searchType);
            }
            switch($fieldSet[2])
            {
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
            if('in' == $fieldSet[2] || 'ni' == $fieldSet[2])
            {
                $valueSign = '(?)';
            } else {
                $valueSign = '?';
            }

            // null and not null
            if(null == $value)
            {
                if('eq' == $fieldSet[2])
                {
                    $query->andWhere($alias . $fieldSet[0] . ' IS NULL');
                    continue;
                }
                elseif('ne' == $fieldSet[2])
                {
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
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的偏移配置
     * @return object 当前对象
     */
    public function addOffsetToQuery(Qwin_Metadata $meta, Doctrine_Query $query, $addition = null)
    {
        $offset = 0;
        if(null != $addition)
        {
            $addition = intval($addition);
            if(0 < $addition)
            {
                $offset = $addition;
            }
        }
        $query->offset($offset);
        return $this;
    }

    /**
     * 为Doctrine查询对象增加限制语句
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的限制配置
     * @return object 当前对象
     */
    public function addLimitToQuery(Qwin_Metadata $meta, Doctrine_Query $query, $addition = null)
    {
        $limit = 0;
        if(null != $addition)
        {
            $addition = intval($addition);
            if(0 < $addition)
            {
                $limit = $addition;
            }
        }
        $query->limit($limit);
        return $this;
    }

    public function addMetadataToQuery(Qwin_Metadata $meta, $name, $query)
    {
        if(!isset($meta['model'][$name]))
        {
            return false;
        }
        $relatedModel = Qwin::run($meta['model'][$name]['name']);
        $relatedMeta = Qwin_Metadata_Manager::get($meta['model'][$name]['metadata']);

        // 设置字段
        $queryField = $relatedMeta['field']->getAttrList(array('isDbField', 'isDbQuery'));
        foreach($queryField as $field)
        {
            $relatedModel->hasColumn($field);
        }
        
        // 设置模型关系
        call_user_func(
            array($relatedModel, 'hasOne'),
            $meta['model'][$name]['name'] . ' as ' . $meta['model'][$name]['alias'],
                array(
                    'local' => $meta['model'][$name]['local'],
                    'foreign' => $meta['model'][$name]['foreign']
                )
        );
        

        $query->leftJoin('Trex_Article_Model_Article' . '.' . $meta['model'][$name]['alias'] . ' ' . $meta['model'][$name]['alias']);
return true;
        /*$config = Qwin::run('-ini')->getConfig();
        // 设置数据表
        $model->setTableName($config['db']['prefix'] . $meta['db']['table']);
         // 数据库查询的字段数组
        $fieldList = $this->getSettingList($meta['field'], 'isDbQuery');
        foreach($fieldList as $val)
        {
            $model->hasColumn($val);
        }
        return $model;*/
        

        foreach($metaObj['model'] as $model)
        {
            // 不连接数据关联的模型
            if('relatedDb' == $model['type'])
            {
                continue;
            }
            Qwin::load($model['metadata']);
            $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);
            $queryField = $linkedMetaObj->field->getAttrList(array('isDbField', 'isDbQuery'));

            $linkedModelObj = Qwin::run($model['name']);
            $linkedModelObj->setTableName($this->getTablePrefix() . $linkedMetaObj['db']['table']);
            foreach($queryField as $field)
            {
                $linkedModelObj->hasColumn($field);
            }

            // 设置模型关系
            call_user_func(
                array($modelObj, 'hasOne'),
                $model['name'] . ' as ' . $model['alias'],
                    array(
                        'local' => $model['local'],
                        'foreign' => $model['foreign']
                    )
            );
            $query->leftJoin($modelName . '.' . $model['alias'] . ' ' . $model['alias']);
        }
    }

    /**
     * 加载关联的元数据,即对model键名中的元数据进行初始化
     * 不在元数据初始化时加载,是为了减少不必要的资源消耗
     *
     * @param Qwin_Metadata $meta 元数据对象
     * @param mixed $modelType 加载的模型类型
     * @return object 当前对象
     */
    public function loadRelatedMetadata(Qwin_Metadata $meta, $modelType = null)
    {
        if(is_string($modelType))
        {
            $modelType = array($modelType);
        }
        foreach($meta['model'] as $name => $model)
        {
            // 已加载
            if(isset($meta['metadata'][$name]))
            {
                continue;
            }
            if(null == $modelType || in_array($model['type'], $modelType))
            {
                $meta['metadata'][$name] = Qwin_Metadata_Manager::get($model['metadata']);
            }
        }
        return $this;
    }

    public function getFormFieldMetadata(Qwin_Metadata $meta)
    {
       
        $mainMetaField = clone $meta['field'];
        $formField = new Qwin_Metadata_Element_Field();
        // 创建一个纯字段的元数据,用于表单
        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            $tmpMeta = array();
            foreach($relatedMeta['field'] as $field)
            {
                // 存储原来的名称
                $field['form']['_oldName'] = $field['form']['name'];
                $field['form']['_arrayName'] = $name . '[' . $field['form']['name'] . ']';
                $field['form']['name'] = $name . '[' . $field['form']['name'] . ']';//$name . '_' . $field['form']['name'];
                if($field['form']['_oldName'] == $field['form']['id'])
                {
                    $field['form']['id'] = str_replace('_', '-', $field['form']['name']);
                }
                $tmpMeta[$field['form']['name']] = $field;
            }
            $mainMetaField->addData($tmpMeta);
        }
        p($mainMetaField);exit;
        return $mainMetaField;
    }

    /**
     * 数据转换,用于 edit 等二维数组的数据
     * 该方法包含两部分的转换,一个是元数据的转换配置,一个是控制器的转换方法
     *
     * @param array $data 准备转换的数据
     * @param string $action 转换的行为,例如Add,Edit
     * @param Qwin_Metadata $meta 元数据
     * @param Qwin_Trex_Controller $controller 控制器
     * @return array 经过转换的数据
     */
    public function convertOne($data, $action,
        Qwin_Metadata $meta = null,
        Qwin_Trex_Controller $controller = null, $isView = false)
    {
        null == $meta && $meta = Qwin::run('-meta');
        $dataCopy = $data;
        $action = strtolower($action);
        $url = Qwin_Class::run('Qwin_Url');

        // TODO 1.链接问题 2.模块之间的转换
        if($isView)
        {
            foreach($meta['model'] as $model)
            {
                if('view' == $model['type'])
                {
                    foreach($model['fieldMap'] as $localField => $foreignField)
                    {
                        !isset($data[$model['alias']][$foreignField]) && $data[$model['alias']][$foreignField] = '';
                        $data[$localField] = $data[$model['alias']][$foreignField];
                    }
                }
            }
        }
        
        // 对自身域进行转换
        foreach($meta['field'] as $field)
        {
            $name = $field['form']['name'];

            // 初始化两数组的值,如果不存在,则设为空
            if(isset($data[$name]))
            {
                'NULL' == $data[$name] && $data[$name] = null;
                $newData[$name] = $data[$name];
            } else {
                $newData[$name] = $data[$name] = null;
            }

            // 根据元数据中转换器的配置进行转换
            if(isset($field['converter'][$action]) && is_array($field['converter'][$action]))
            {
                $newData[$name] = $this->convert($field['converter'][$action], $data[$name]);
            }

            // 使用控制器中的方法进行转换
            if(null != $controller)
            {
                $methodName = str_replace(array('_', '-'), '', 'convert' . $action . $name);
                if(method_exists($controller, $methodName))
                {
                    $newData[$name] = call_user_func_array(
                        array($controller, $methodName),
                        array($newData[$name], $name, $newData, $dataCopy)
                    );
                }
            }

            /**
             * 增加Url查询
             * @todo 是否应该出现在此
             */
            if(true == $isView && $meta['field'][$name]['attr']['isLink'] && null != $controller)
            {
                !isset($dataCopy[$name]) && $dataCopy[$name] = null;
                $newData[$name] = '<a href="' . $url->createUrl($controller->_set, array('action' => 'Index', 'searchField' => $name, 'searchValue' => $dataCopy[$name])) . '">' . $newData[$name] . '</a>';
            }
        }

        // 对关联域进行转换
        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            !isset($data[$name]) && $data[$name] = array();
            $controller = Qwin::run($meta['model'][$name]['controller']);
            $newData[$name] = $this->convertOne($data[$name], $action, $relatedMeta, $controller);
        }

        return $newData;
    }

    /**
     * 根据配置转换一个值
     *
     * @param array $set 数组配置
     * @param mixed  要转换的值
     * @return mixed 转换结果
     */
    public function convert($set, $value)
    {
        array_unshift($set, null);
        $set[0] = $set[1];
        $set[1] = $value;
        return Qwin::callByArray($set, $value);
    }

    /**
     * 数据转换,用于 list 等三位数组的数据
     *
     * @param array $data 准备转换的数据
     * @param string $action 转换的行为,例如Add,Edit
     * @param Qwin_Metadata $meta 元数据
     * @param Qwin_Trex_Controller $controller 控制器
     * @return array 经过转换的数据
     */
    public function convertArray($data, $action,
        Qwin_Metadata $meta = null,
        Qwin_Trex_Controller $controller = null,
        $isView = false)
    {
        foreach($data as &$row)
        {
            $row = $this->convertOne($row, $action, $meta, $controller, $isView);
        }
        return $data;
    }

    /**
     * 获取 url 中的数据
     *
     * @param array $data add.edit等操作传过来的初始数据
     * @param int $mode
     */
    public function getUrlData($data)
    {
        foreach($data as $key => $val)
        {
            if(isset($_GET['_data'][$key]) && '' != $_GET['_data'][$key])
            {
                $data[$key] = $_GET['_data'][$key];
            }
        }
        return $data;
    }

    public function getDefaultLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach($meta['field'] as $name => $field)
        {
            $group = $field['basic']['group'];
            if(!isset($layout[$group]))
            {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while(isset($layout[$group][$order]))
            {
                $order++;
            }
            
            if(!$relatedName)
            {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach($meta['metadata'] as $key => $relatedMeta)
        {
            if('db' == $meta['model'][$key]['type'])
            {
                $layout = $this->getDefaultLayout($relatedMeta, $layout, $key);
            }
        }

        // 根据键名排序
        if(!$relatedName)
        {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getViewLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach($meta['field'] as $name => $field)
        {
            if(0 == $field['attr']['isView'])
            {
                continue;
            }
            
            $group = $field['basic']['group'];
            if(!isset($layout[$group]))
            {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while(isset($layout[$group][$order]))
            {
                $order++;
            }

            if(!$relatedName)
            {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach($meta['metadata'] as $key => $relatedMeta)
        {
            if('db' == $meta['model'][$key]['type'])
            {
                $layout = $this->getViewLayout($relatedMeta, $layout, $key);
            }
        }

        // 根据键名排序
        if(!$relatedName)
        {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getEditLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach($meta['field'] as $name => $field)
        {
            if(1 == $field['attr']['isReadonly'] || 'custom' == $field['form']['_type'])
            {
                // TODO
                $meta['field']->set($name . '.form._type', 'hidden');
                //continue;
            }

            $group = $field['basic']['group'];
            if(!isset($layout[$group]))
            {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while(isset($layout[$group][$order]))
            {
                $order++;
            }

            if(!$relatedName)
            {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach($meta['metadata'] as $key => $relatedMeta)
        {
            if('db' == $meta['model'][$key]['type'])
            {
                $layout = $this->getEditLayout($relatedMeta, $layout, $key);
            }
        }

        // 根据键名排序
        if(!$relatedName)
        {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getListLayout(Qwin_Metadata $meta, array $layout = null, $relatedName = false)
    {
        null == $layout && $layout = array();
        foreach($meta['field'] as $name => $field)
        {
            if(1 != $field['attr']['isList'])
            {
                continue;
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while(isset($layout[$order]))
            {
                $order++;
            }

            if(!$relatedName)
            {
                $layout[$order] = $field['form']['name'];
            } else {
                $layout[$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }

        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            if('db' == $meta['model'][$name]['type'])
            {
                $layout += $this->getListLayout($relatedMeta, $layout, $name);
            }
        }

        // 根据键名排序
        if(!$relatedName)
        {
            ksort($layout);
        }

        return $layout;
    }

    public function convertTojqGridData($data, $primaryKey, $layout)
    {
        $i = 0;
        $rowData = array();
        $nullData = '<em>(null)<em>';
        foreach($data as $row)
        {
            $rowData[$i][$primaryKey] = $row[$primaryKey];
            foreach($layout as $field)
            {
                if(is_array($field))
                {
                    if(isset($row[$field[0]][$field[1]]))
                    {
                        $rowValue = $row[$field[0]][$field[1]];
                    } else {
                        // 使列表 null 类型数据能正确显示
                        $rowValue = $nullData;
                    }
                } else {
                    if(isset($row[$field]))
                    {
                        $rowValue = $row[$field];
                    } else {
                        $rowValue = $nullData;
                    }
                }
                $rowData[$i]['cell'][] = $rowValue;
            }
            $i++;
        }
        return $rowData;
    }

    public function getAddLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach($meta['field'] as $name => $field)
        {
            if('custom' == $field['form']['_type'])
            {
                continue;
            }

            $group = $field['basic']['group'];
            if(!isset($layout[$group]))
            {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while(isset($layout[$group][$order]))
            {
                $order++;
            }

            if(!$relatedName)
            {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach($meta['metadata'] as $key => $relatedMeta)
        {
            if('db' == $meta['model'][$key]['type'])
            {
                $layout = $this->getAddLayout($relatedMeta, $layout, $key);
            }
        }

        // 根据键名排序
        if(!$relatedName)
        {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    /**
     * 将多维数组转换为一维
     *
     * @param array $data 多维数组
     * @return array 一维数组
     */
    public function convertDataToSingle($data)
    {
        if(isset($data[0]))
        {
            foreach($data as $key => $row)
            {
                $data[$key] = $this->convertDataToSingle($row);
            }
        } else {
            foreach($data as $key => $val)
            {
                if(is_array($val))
                {
                    foreach($val as $key2 => $val2)
                    {
                        $data[$key . '_' . $key2] = $val2;
                    }
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    /**
     * 还原一维数组为二维数组
     *
     * @param object $meta 转换过的元数据
     * @param array $data 入库数据
     * @return 新数据
     */
    public function restoreData($restoreMeta, $meta, $data)
    {
        foreach($restoreMeta as $key => $field)
        {
            if(!isset($data[$key]))
            {
                continue;
            }
            if(!isset($meta[$key]['form']['_oldName']))
            {
                $newData[$key] = $data[$key];
            } else {
                $modelName = str_replace('_' . $meta[$key]['form']['_oldName'], '', $key);
                !isset($newData[$modelName]) && $newData[$modelName] = array();
                $newData[$modelName][$meta[$key]['form']['_oldName']] = $data[$key];
            }
        }
        return $newData;
    }

    /**
     * 验证一个域的数据
     *
     * @param string $name 域的名称
     * @param string $meta 域的验证配置
     * @param string $data 包含各域的值的数组,例如从数据库取出或客户端提交的数据
     * @param object $controller 控制器对象
     * @return true/Qwin_Validator_Result true表示通过验证,Qwin_Validator_Result表示不通过,对象中包含错误信息
     */
    public function validateOne($name, $data, $validator, Qwin_Trex_Controller $controller = null)
    {
        // 根据控制器的方法进行验证
        if(null != $controller)
        {
            $method = 'validate' . $name;
            !isset($data[$name]) && $data[$name] = null;
            $result = Qwin::callByArray(array(
                array($controller, $method),
                $data[$name],
                $name,
                $data,
            ));
            // 除了返回错误的对象外,其他都认为是验证通过
            if(is_object($result) && 'Qwin_Validator_Result' == get_class($result))
            {
                return $result;
            }
        }

        // 根据元数据进行验证
        if(empty($validator['rule']))
        {
            return true;
        }
        $ext = Qwin::run('Qwin_Class_Extension');
        foreach($validator['rule'] as $rule => $param)
        {
            $class = $ext->getClass($rule);
            if(false == $class)
            {
                return true;
            }
            $array = array(
                array($class, $rule),
                $data[$name],
                $param,
            );
            if(false === Qwin::callByArray($array))
            {
                return new Qwin_Validator_Result(false, $name, $validator['message'][$rule], 0, $param);
            }
        }
        return true;
    }

    /**
     * 验证一组域的数据
     *
     * @param array $data 验证的数据
     * @param array $data 元数据对象
     * @param object $controller 控制器对象
     * @return true|Qwin_Validate_Result 是否通过验证,true表示通过,Qwin_Validate_Result对象是包含错误信息的返回结果
     */
    public function validateArray($data, Qwin_Metadata $meta = null, Qwin_Trex_Controller $controller = null)
    {
        // 验证自身域
        foreach($meta['field'] as $field)
        {
            $result = $this->validateOne($field['form']['name'], $data, $field['validator'], $controller);
            // 返回错误信息
            if(true !== $result)
            {
                return $result;
            }
        }

        // 验证关联域
        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            !isset($data[$name]) && $data[$name] = array();
            $controller = Qwin::run($meta['model'][$name]['controller']);
            $result = $this->validateArray($data[$name], $relatedMeta, $controller);
            // 返回错误信息
            if(true !== $result)
            {
                $result->field = array($name, $result->field);
                return $result;
            }
        }
        
        return true;
    }

    /**
     * 将域的元数据转换成jQuery Validate 的验证配置数组
     *
     * @param array $fieldMeta 域的元数据配置
     * @return array jQuery Validate 的验证配置数组
     */
    public function getJQueryValidateCode($meta, $relatedName = false)
    {
        $lang = Qwin::run('-lang');
        $validation = array(
            'rules' => array(),
            'messages' => array(),
        );
        foreach($meta['field'] as $name => $field)
        {
            if(empty($field['validator']['rule']))
            {
                continue;
            }
            foreach($field['validator']['rule'] as $rule => $param)
            {
                if($relatedName)
                {
                    $name = $relatedName . '[' . $name . ']';
                }
                $validation['rules'][$name][$rule] = $param;
                $validation['messages'][$name][$rule] = $this->format($lang->t($field['validator']['message'][$rule]), $param);
            }
        }

        // 关联元数据
        /*foreach($meta['model'] as $model)
        {
            if('db' != $model['type'])
            {
                continue;
            }
            $relatedMeta = Qwin_Metadata_Manager::get($model['metadata']);
            $tempValidation = $this->getJQueryValidateCode($relatedMeta, $model['alias']);
            $validation['rules'] += $tempValidation['rules'];
            $validation['messages'] += $tempValidation['messages'];
        }*/
        return $validation;
    }

    /**
     * 删除只读键的值
     *
     * @param array $data
     * @param Qwin_Metadata $meta 元数据对象
     * @return array
     */
    public function deleteReadonlyValue($data, Qwin_Metadata $meta)
    {
        foreach($meta['field'] as $field)
        {
            if($field['attr']['isReadonly'])
            {
                unset($data[$field['form']['name']]);
            }
        }
        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            $this->deleteReadonlyValue($data[$name], $relatedMeta);
        }
        return $data;
    }

    /**
     * 设置外键的值,保证数据之间能正确关联
     *
     * @param Qwin_Metadata_Element_Model $modelList 模型配置元数据
     * @param array $data 经过转换的用户提交的数据
     * @return array 设置的后的值
     */
    public function setForeignKeyData($modelList, $data)
    {
        foreach($modelList as $model)
        {
            if('db' == $model['type'])
            {
                $data[$model['alias']][$model['foreign']] = $data[$model['local']];
            }
        }
        return $data;
    }

    /**
     * 删除主键的的值
     *
     * @param array $data
     * @param Qwin_Metadata $meta 元数据对象
     * @return array
     */
    public function unsetPrimaryKeyValue($data, Qwin_Metadata $meta)
    {
        $primaryKey = $meta['db']['primaryKey'];
        // 允许自定义主键的值
        /*if(isset($data[$primaryKey]))
        {
            $data[$primaryKey] = null;
            //unset($data[$primaryKey]);
        }*/
        foreach($meta['metadata'] as $name => $relatedMeta)
        {
            $primaryKey = $relatedMeta['db']['primaryKey'];
            if(isset($data[$name][$primaryKey]))
            {
                $data[$name][$primaryKey] = null;
                //unset($data[$name][$primaryKey]);
            }
        }
        return $data;
    }

    /**
     * 增加点击
     *
     *
     */
    public function addHit($table, $id, $field = 'hit', $num = 1)
    {
        Qwin::run('-qry')->setTable($table);

        $sql = "UPDATE " . Qwin::run('-qry')->getTable() . " SET `$field` = $field+$num WHERE `id` = '$id'; ";
        Qwin::run('-db')->Query($sql);
    }

    public function getUrlList($field, $url_data)
    {
        Qwin::run('-arr')->set($url_data);
        $data = array();
        foreach($url_data as $key => $val)
        {
            if('' != $val && isset($url_data[$field[$key]['form']['name']]))
            {
                $data[$field[$key]['form']['name']] = $field[$key]['form']['name'];
            }
        }
        return $data;
    }


    // TODO !!
    public function convertUrlQuery2($data, $sql_data)
    {
        foreach($data as $key => $val)
        {
            if(isset($this->__meta['field'][$key]['list']['isLink']) && $this->__meta['field'][$key]['list']['isLink'] == true)
            {
                $data[$key] = '<a href="' . url(array('admin', $this->_set['controller']), array(_S('url', '_DATA') . '%5B' . $key . '%5D' => $sql_data[$key])) . '">' . $val . '</a>';
            }
        }
        return $data;
    }

    public function getTipData($set)
    {
        $validatorMessage1 = $this->getCommonClassList('validator_message', 'rsc');
        $validatorMessage2 = $this->getCommonClassList('validator_message');
        $validatorMessage = array_combine($validatorMessage1, $validatorMessage2);

        $tipData = array();
        foreach($set as $field)
        {
            // 读取域描述
            if(isset($field['basic']['descrip']) && '' != $field['basic']['descrip'])
            {
                if(is_array($field['basic']['descrip']))
                {
                    foreach($field['basic']['descrip'] as $tip)
                    {
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-info',
                            'data' => $tip,
                            'id' => '',
                        );
                    }
                } else {
                    $tipData[$field['form']['name']][] = array(
                        'icon' => 'ui-icon-info',
                        'data' => $field['basic']['descrip'],
                        'id' => '',
                    );
                }
            }
            // 读取验证信息
            $validator = &$field['validator'];
            if(isset($validator['rule']) && 0 != count($validator['rule']))
            {
                $validator['rule'] = $this->makeRequiredAtFront($validator['rule']);
                foreach($validator['rule'] as $method => $val)
                {
                    if(isset($validator['message'][$method]) && '' != $validator['message'][$method])
                    {
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-alert',
                            'data' => $validator['message'][$method],
                            'id' => 'validator-method-' . $method,
                        );
                    // 错误消息为空,需加载默认消息
                    } else {
                        $msg = '';
                        if(isset($validatorMessage[$method]))
                        {
                            $msg = $validatorMessage[$method];
                        }
                        $msg = $this->format($msg, $val);
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-alert',
                            'data' => $msg,
                            'id' => 'validator-' . $field['form']['name'] . '-' . $method,
                        );
                    }
                }
            }
        }
        return $tipData;
    }

    public function makeRequiredAtFront($rule)
    {
        // 将必填项放在数组第一位
        if(array_key_exists('required', $rule))
        {
            $tmpArr = array(
                'required' => true,
            );
            unset($rule['required']);
            $rule = $tmpArr + $rule;
        }
        return $rule;
    }

    /**
     * 模拟jquery.format转换数据,将{i}替换为$replace[i]的值
     *
     * @param string $data 代转换的数据
     * @param array $repalce 转换的数组
     * @return string 转换后的数据
     */
    public function format($data, $repalce)
    {
        $repalce = (array)$repalce;
        $pos = strpos($data, '{0}');
        if(false !== $pos)
        {
            $repalce = Qwin::run('-arr')->set($repalce);
            $search = array();
            $count = count($repalce);
            for($i = 0;$i < $count; $i++)
            {
                $search[$i] = '{' . $i . '}';
            }
            $data = str_replace($search, $repalce, $data);
        }
        return $data;
    }

    public function metadataToModel($meta, $model)
    {
        $config = Qwin::run('-ini')->getConfig();
        // 设置数据表
        $model->setTableName($config['db']['prefix'] . $meta['db']['table']);
         // 数据库查询的字段数组
        $fieldList = $this->getSettingList($meta['field'], 'isDbQuery');
        foreach($fieldList as $val)
        {
            $model->hasColumn($val);
        }
        return $model;
    }

    /**
     * 将数据库类的二维数组转换为表单资源数组
     * @param array $data 从数据库或是缓存文件获取的数组
     * @param string/int $key 第一个键名,作为资源的键名
     * @param string/int $key2 第二个键名,作为资源值的键名
     * @return array 表单资源数组
     * @todo 以conver开头的方法,可能会出现冲突
     */
    public function convertDbDataToFormResource($data, $key, $key2)
    {
        $resource = array();
        foreach($data as  $row)
        {
            $resource[$row[$key]] = $row[$key2];
        }
        return $resource;
    }

    function createLayoutArr($fieldArr, $col = 2)
    {
        $layoutArr = array();
        $x = 0;
        $y = -1;
        $tmpCol = $col - 1;
        $banType = array('hidden', 'custom');
        foreach($fieldArr as $field)
        {
            // 隐藏域不占空间
            if(in_array($field['form']['_type'], $banType))
            {
                continue;
            }
            // 初始化数组
            if(0 == $x)
            {
                $y++;
                $layoutArr[$y] = array();
            }
            $layoutArr[$y][$x] = $field['form']['name'];
            $tmpCol == $x++ && $x = 0;
        }
        return $layoutArr;
    }

    public function saveRelatedDbData($meta, $data, $query)
    {
        $ctrler = Qwin::run('-controller');
        foreach($meta['model'] as $model)
        {
            if('relatedDb' == $model['type'])
            {
                /*
                 * 检验是否要保存数据
                 */
                $method = 'isSave' . $model['alias'] . 'Data';
                if(!method_exists($ctrler, $method)
                    || false === call_user_func_array(
                        array($ctrler, $method),
                        array($data, $query)
                        )){
                    return false;
                }
                    
                $relatedData = array();
                foreach($model['fieldMap'] as $localField => $foreignField)
                {
                    if(isset($data[$localField]))
                    {
                        $relatedData[$foreignField] = $data[$localField];
                    } elseif(isset($_POST[$localField])) {
                        $relatedData[$foreignField] = $_POST[$localField];
                    } else {
                        $relatedData[$foreignField] = null;
                    }
                }
                $relatedDbMeta = Qwin_Metadata_Manager::get($model['metadata']);
                // TODO 补全其他转换方式,分离该过程
                $copyData = $relatedData;
                foreach($relatedDbMeta['field'] as $name => $field)
                {
                    $methodName = str_replace(array('_', '-'), '', 'convertdb' .  $model['alias'] . $name);
                    if(method_exists($ctrler, $methodName))
                    {
                        !isset($relatedData[$name]) && $relatedData[$name] = null;
                        $relatedData[$name] = call_user_func_array(
                            array($ctrler, $methodName),
                            array($relatedData[$name], $name, $relatedData, $copyData)
                        );
                    }
                }

                /**
                 * 保存数据
                 */
                $relatedDbQuery = $meta->getDoctrineQuery($model['set']);
                $ini = Qwin::run('-ini');
                $modelName = $ini->getClassName('Model', $model['set']);
                $relatedDbQuery = new $modelName;
                $relatedDbQuery->fromArray($relatedData);
                $relatedDbQuery->save();
            }
        }
    }
}
