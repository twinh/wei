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
 * @todo        分离出验证类,转换类,Doctrine处理类等
 */

class Qwin_Trex_Metadata extends Qwin_Metadata
{
    /**
     * @var array $_convertOption   数据转换的选项
     *
     *      -- view                 是否根据视图类关联模型的配置进行转换
     *                              提示,如果转换的数据作为表单的值显示,应该禁止改选项
     *
     *      -- null                 是否将NULL字符串转换为null类型
     *
     *      -- type                 是否进行强类型的转换,类型定义在['fieldName]['db']['type']
     *
     *      -- meta                 是否使用元数据的converter配置进行转换
     *
     *      -- converter            是否使用转换器进行转换
     *
     *      -- relatedMeta          是否转换关联的元数据
     */
    protected $_convertOption = array(
        'view'          => true,
        'null'          => true,
        'type'          => true,
        'meta'          => true,
        'converter'     => true,
        'link'          => true,
        'relatedMeta'   => true,
    );

    /**
     * @var array $_validateOption  数据验证的选项
     *
     *      -- meta                 是否根据元数据的验证配置进行转换
     *
     *      -- validator            是否根据验证器进行验证
     *
     */
    protected $_validateOption = array(
        'meta'          => true,
        'validator'     => true,
    );

    /**
     *
     * @var array $_queryOption 查询对象的选项
     *
     *      -- model                 模型的别名组成的数组
     *
     *      -- type                  模型的类型组成的数组
     *
     */
    protected $_queryOption = array(
        'model'         => array(),
        'type'          => array(),
    );

    /**
     * 数据表前缀
     * @var string
     */
    protected $_tablePrefix;

    /**
     * 获取数据表前缀,方便调用
     *
     * @return string 表前缀
     */
    public function getTablePrefix($adapter = null)
    {
        if(null != $this->_tablePrefix)
        {
            return $this->_tablePrefix;
        }
        if (null == $adapter) {
            $config = Qwin::run('-ini')->getConfig();
            $adapter = $config['database']['mainAdapter'];
        }
        $this->_tablePrefix = $config['database']['adapter'][$adapter]['prefix'];
        return $this->_tablePrefix;
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
     * 将元数据的域定义,数据表定义加入模型中
     *
     * @param Qwin_Metadata $meta 元数据对象
     * @param Doctrine_Record $model Doctrine对象
     * @return Qwin_Trex_Metadata 当前对象
     */
    public function metadataToModel(Qwin_Metadata $meta, Doctrine_Record $model)
    {
        $tablePrefix = $this->getTablePrefix();

        // 设置数据表
        $model->setTableName($tablePrefix . $meta['db']['table']);

        // 设置字段
        $fieldList = $meta['field']->getAttrList(array('isDbField', 'isDbQuery'));
        foreach ($fieldList as $field) {
            $model->hasColumn($field);
        }
        return $this;
    }

    /**
     * 根据元数据配置,获取Doctrine的查询对象
     *
     * @param array $set 元数据配置
     */
    public function getQueryBySet($set, $option = false)
    {
        $metaClass  = $this->getClassName('Metadata', $set);
        $modelClass = $this->getClassName('Model', $set);
        $meta       = Qwin_Metadata_Manager::get($metaClass);
        $model      = Qwin::run($modelClass);
        return $this->getQuery($meta, $model, $option);
    }

    /**
     * 通过元数据配置,获取Doctrine的查询对象
     *
     * @param Qwin_Metadata $meta 元数据配置
     * @param Doctrine_Record $model 模型对象
     * @return Doctrine_Query 查询对象
     * @todo 缓存查询对象,模型对象
     * @todo padb问题
     */
    public function getQuery(Qwin_Metadata $meta, Doctrine_Record $model = null, $option = false)
    {
        // 未定义模型,则初始化关联模型
        if (null == $model) {
            $set = $meta->getSetFromClass();
            $modelClass = $this->getClassName('Model', $set);
            $model = Qwin::run($modelClass);
        } else {
            $modelClass = get_class($model);
        }

        $joinModel = array();
        // 取出要关联的模块
        if (false != $option) {
            $option = array_merge($this->_queryOption, $option);
            foreach ($meta['model'] as $modelName => $modelSet) {
                if (in_array($modelName, $option['model']) || in_array($modelSet['type'], $option['type'])) {
                    $joinModel[$modelName] = $modelName;
                }
            }            
        }

        // 自身的转换
        $this->metadataToModel($meta, $model);
        $query = Doctrine_Query::create()->from($modelClass);


        // 关联模型的转换
        foreach ($joinModel as $joinModelName) {
            // 该模型的设置
            $modelSet = $meta['model'][$joinModelName];
            $relatedMetaObject = Qwin_Metadata_Manager::get($modelSet['metadata']);
            $relatedModelObejct = Qwin::run($modelSet['name']);
            $this->metadataToModel($relatedMetaObject, $relatedModelObejct);

            // 设置模型关系
            call_user_func(
                array($model, 'hasOne'),
                $modelSet['name'] . ' as ' . $modelSet['alias'],
                array(
                    'local' => $modelSet['local'],
                    'foreign' => $modelSet['foreign']
                )
            );
            $query->leftJoin($modelClass . '.' . $modelSet['alias'] . ' ' . $modelSet['alias']);
        }
        return $query;
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
        foreach ($meta['model'] as $model) {
            Qwin::load($model['metadata']);
            $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);

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
     * 获取Url中的排序配置,对字段域不做验证,字段域的验证应该是交给服务执行的.
     *
     * @param string $fieldName 字段域的名称
     * @param string $typeName 排序的名称
     * @return array 标准元数据的排序配置
     */
    public function getUrlOrder($fieldName = 'orderField', $typeName = 'orderType')
    {
        $request = Qwin::run('-request');

        $orderField = $request->g('orderField');

        // 地址未设置排序
        if (null == $orderField) {
            return array();
        }

        $orderType = strtoupper($request->g('orderType'));
        $typeOption = array('DESC', 'ASC');
        if (!in_array($orderType, $typeOption)) {
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
        if (null == $searchField) {
            return array();
        }

        $searchSet = array();
        $searchValue = $request->g($valueName);
        $searchOper  = $request->g($operName);
        if (!is_array($searchField)) {
            $searchSet[] = array($searchField, $searchValue, $searchOper);
        } else {
            foreach ($searchField as $key => $value) {
                !isset($searchValue[$key]) && $searchValue[$key] = null;
                !isset($searchOper[$key]) && $searchOper[$key] = null;
                $searchSet[] = array(
                    $searchField[$key],
                    $searchValue[$key],
                    $searchOper[$key],
                );
            }
        }
        return $searchSet;
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
        if (null != $list) {
            $list = explode($delimiter, $list);
            foreach ($list as $key => $value) {
                $pos = strpos($value, '.');
                if (false !== $pos) {
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
        if (!class_exists($metadataName)) {
            return null;
        }
        $meta = Qwin_Metadata_Manager::get($metadataName);
        return $meta['db']['primaryKey'];
    }

    public function getMetadataBySet(array $set)
    {
        $ini = Qwin::run('-ini');
        $metadataName = $ini->getClassName('Metadata', $set);
        if (class_exists($metadataName)) {
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
            if(null == $value) {
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
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的偏移配置
     * @return object 当前对象
     */
    public function addOffsetToQuery(Qwin_Metadata $meta, Doctrine_Query $query, $addition = null)
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
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的限制配置
     * @return object 当前对象
     */
    public function addLimitToQuery(Qwin_Metadata $meta, Doctrine_Query $query, $addition = null)
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
     * 数据转换,用于 edit 等二维数组的数据
     * 该方法包含两部分的转换,一个是元数据的转换配置,一个是转换器的转换方法
     *
     * @param array $data 准备转换的数据
     * @param string $action 转换的行为,例如Add,Edit
     * @param Qwin_Metadata $meta 元数据
     * @param object $convertObject 转换器的对象,默认是元数据自身
     * @return array 经过转换的数据
     */
    public function convertOne($data, $action, Qwin_Metadata $meta, $convertObject = null, $option = array())
    {
        $option = array_merge($this->_convertOption, $option);
        $dataCopy = $data;
        $action = strtolower($action);

        if ($option['view']) {
            foreach ($meta['model'] as $model) {
                if ('view' == $model['type']) {
                    foreach ($model['fieldMap'] as $localField => $foreignField) {
                        !isset($data[$model['alias']][$foreignField]) && $data[$model['alias']][$foreignField] = '';
                        $data[$localField] = $data[$model['alias']][$foreignField];
                    }
                }
            }
        }
        
        // 对自身域进行转换
        foreach ($meta['field'] as $field) {
            $name = $field['form']['name'];

            // 初始化两数组的值,如果不存在,则设为空
            if (isset($data[$name])) {
                'NULL' == $data[$name] && $data[$name] = null;
                $newData[$name] = $data[$name];
            } else {
                $newData[$name] = $data[$name] = null;
            }

            // 类型转换
            if ($option['type']) {
                if (null != $newData[$name]) {
                    if ('string' == $field['db']['type']) {
                        $newData[$name] = (string)$newData[$name];
                    } elseif ('integer' == $field['db']['type']) {
                        $newData[$name] = (int)$newData[$name];
                    } elseif ('float' == $field['db']['type']) {
                        $newData[$name] = (float)$newData[$name];
                    } elseif ('array' == $field['db']['type']) {
                        $newData[$name] = (array)$newData[$name];
                    }
                }
            }

            // 根据元数据中转换器的配置进行转换
            if ($option['meta'] && isset($field['converter'][$action]) && is_array($field['converter'][$action])) {
                $newData[$name] = $this->convert($field['converter'][$action], $data[$name]);
            }

            // 使用转换器中的方法进行转换
            if ($option['converter'] && null != $convertObject) {
                $methodName = str_replace(array('_', '-'), '', 'convert' . $action . $name);
                if (method_exists($convertObject, $methodName)) {
                    $newData[$name] = call_user_func_array(
                        array($convertObject, $methodName),
                        array($newData[$name], $name, $newData, $dataCopy)
                    );
                }
            }

            // 转换链接
            if ($option['link'] && 1 == $field['attr']['isLink'] && method_exists($convertObject, 'setIsLink')) {
                $newData[$name] = call_user_func_array(
                    array($convertObject, 'setIsLink'),
                    array($newData[$name], $name, $newData, $dataCopy, $action)
                );
            }
        }

        // 对db类型的关联元数据进行转换
        if ($option['relatedMeta']) {
            foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
                !isset($data[$name]) && $data[$name] = array();
                // 不继续转换关联元数据
                $option['relatedMeta'] = false;
                $newData[$name] = $this->convertOne($data[$name], $action, $relatedMeta, $relatedMeta, $option);
            }
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
     * @param object $convertObject 转换器的对象,默认是元数据自身
     * @return array 经过转换的数据
     */
    public function convertArray($data, $action, Qwin_Metadata $meta, $convertObject = null, $isView = false)
    {
        foreach ($data as &$row) {
            $row = $this->convertOne($row, $action, $meta, $convertObject, $isView);
        }
        return $data;
    }
    
    public function getModelMetadataByType($meta, $type)
    {
        $result = array();
        foreach ($meta['model'] as $name => $model) {
            if ($model['enabled'] && $type == $model['type']) {
                if (!isset($meta['metadata'][$name])) {
                    $meta['metadata'][$name] = Qwin_Metadata_Manager::get($model['metadata']);
                }
                $result[$name] = $meta['metadata'][$name];
            }
        }
        return $result;
    }

    /**
     * 获取 url 中的数据
     *
     * @param array $data add.edit等操作传过来的初始数据
     * @param int $mode
     */
    public function getUrlData($data)
    {
        foreach ($data as $key => $val) {
            if (isset($_GET['_data'][$key]) && '' != $_GET['_data'][$key]) {
                $data[$key] = $_GET['_data'][$key];
            }
        }
        return $data;
    }

    public function getDefaultLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach ($meta['field'] as $name => $field) {
            $group = $field['basic']['group'];
            if (!isset($layout[$group])) {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$group][$order])) {
                $order++;
            }
            
            if (!$relatedName) {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach ($this->getModelMetadataByType($meta, 'db') as $key => $relatedMeta) {
             $layout = $this->getDefaultLayout($relatedMeta, $layout, $key);
        }

        // 根据键名排序
        if (!$relatedName) {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getViewLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach ($meta['field'] as $name => $field) {
            if (0 == $field['attr']['isView']) {
                continue;
            }
            
            $group = $field['basic']['group'];
            if (!isset($layout[$group])) {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$group][$order])) {
                $order++;
            }

            if (!$relatedName) {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach ($this->getModelMetadataByType($meta, 'db') as $key => $relatedMeta) {
            $layout = $this->getViewLayout($relatedMeta, $layout, $key);
        }

        // 根据键名排序
        if (!$relatedName) {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getEditLayout($meta, array $layout = null, $relatedName = false)
    {
        foreach($meta['field'] as $name => $field) {
            if (1 == $field['attr']['isReadonly'] || 'custom' == $field['form']['_type']) {
                // TODO
                $meta['field']->set($name . '.form._type', 'hidden');
                //continue;
            }

            $group = $field['basic']['group'];
            if (!isset($layout[$group])) {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$group][$order])) {
                $order++;
            }

            if (!$relatedName) {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach($this->getModelMetadataByType($meta, 'db') as $key => $relatedMeta) {
            $layout = $this->getEditLayout($relatedMeta, $layout, $key);
        }

        // 根据键名排序
        if (!$relatedName) {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function getListLayout(Qwin_Metadata $meta, array $layout = null, $relatedName = false)
    {
        null == $layout && $layout = array();
        foreach ($meta['field'] as $name => $field) {
            if (1 != $field['attr']['isList']) {
                continue;
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$order])) {
                $order++;
            }

            if (!$relatedName) {
                $layout[$order] = $field['form']['name'];
            } else {
                $layout[$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }

        foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
            $layout += $this->getListLayout($relatedMeta, $layout, $name);
        }

        // 根据键名排序
        if (!$relatedName) {
            ksort($layout);
        }

        return $layout;
    }

    public function convertTojqGridData($data, $primaryKey, $layout)
    {
        $i = 0;
        $rowData = array();
        $nullData = '<em>(null)<em>';
        foreach ($data as $row) {
            $rowData[$i][$primaryKey] = $row[$primaryKey];
            foreach($layout as $field) {
                if (is_array($field)) {
                    if (isset($row[$field[0]][$field[1]])) {
                        $rowValue = $row[$field[0]][$field[1]];
                    } else {
                        // 使列表 null 类型数据能正确显示
                        $rowValue = $nullData;
                    }
                } else {
                    if (isset($row[$field])) {
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
        foreach ($meta['field'] as $name => $field) {
            if('custom' == $field['form']['_type']) {
                continue;
            }

            $group = $field['basic']['group'];
            if (!isset($layout[$group])) {
                $layout[$group] = array();
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$group][$order])) {
                $order++;
            }

            if (!$relatedName) {
                $layout[$group][$order] = $field['form']['name'];
            } else {
                $layout[$group][$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }
        foreach ($this->getModelMetadataByType($meta, 'db') as $key => $relatedMeta) {
            if ('db' == $meta['model'][$key]['type']) {
                $layout = $this->getAddLayout($relatedMeta, $layout, $key);
            }
        }

        // 根据键名排序
        if (!$relatedName) {
            array_walk($layout, 'ksort');
        }

        return $layout;
    }

    public function setLastViewedItem($meta, $result)
    {
        if (empty($meta['db']['nameKey'])) {
            return false;
        }

        $session = Qwin::run('-session');
        $lang = Qwin::run('-lang');
        
        $item = (array)$session->get('lastViewedItem');
        $key = get_class($meta) . $result[$meta['db']['primaryKey']];

        // 最多保存10项
        if (10 <= count($item)) {
            array_pop($item);
        }

        // 加到第一项
        $item = array(
            $key => array(
            'title' => '[' . $lang->t($meta['page']['title']) .  ']' . $result[$meta['db']['nameKey'][0]],
            'href' => $_SERVER['REQUEST_URI'],
            )
        ) + $item;
        
        $session->set('lastViewedItem', $item);
        return true;
    }

    /**
     * 验证一个域的数据
     *
     * @param string $name 域的名称
     * @param string $meta 域的验证配置
     * @param string $data 包含各域的值的数组,例如从数据库取出或客户端提交的数据
     * @param object|null $validateObj 验证器对象
     * @return true/Qwin_Validator_Result true表示通过验证,Qwin_Validator_Result表示不通过,对象中包含错误信息
     */
    public function validateOne($name, $data, $validator, $validateObj = null, $option = array())
    {
        $option = array_merge($this->_validateOption, $option);

        // 根据验证器的方法进行验证
        if ($option['validator'] && null != $validateObj) {
            $method = 'validate' . str_replace(array('_', '-'), '', $name);
            !isset($data[$name]) && $data[$name] = null;
            $result = Qwin::callByArray(array(
                array($validateObj, $method),
                $data[$name],
                $name,
                $data,
            ));
            // 除了返回错误的对象外,其他都认为是验证通过
            if (is_object($result) && 'Qwin_Validator_Result' == get_class($result)) {
                return $result;
            }
        }

        // 根据元数据进行验证
        if(!$option['validator'] || empty($validator['rule']))
        {
            return true;
        }
        $ext = Qwin::run('Qwin_Class_Extension');
        foreach ($validator['rule'] as $rule => $param) {
            $class = $ext->getClass($rule);
            if (false == $class) {
                return true;
            }
            $array = array(
                array($class, $rule),
                $data[$name],
                $param,
            );
            if (false === Qwin::callByArray($array)) {
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
     * @param object|null $validateObj 转换器对象
     * @return true|Qwin_Validate_Result 是否通过验证,true表示通过,Qwin_Validate_Result对象是包含错误信息的返回结果
     */
    public function validateArray($data, Qwin_Metadata $meta, $validateObj = null)
    {
        // 验证自身域
        foreach ($meta['field'] as $field) {
            $result = $this->validateOne($field['form']['name'], $data, $field['validator'], $validateObj);
            // 返回错误信息
            if (true !== $result) {
                return $result;
            }
        }

        // 验证关联域
        foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
            !isset($data[$name]) && $data[$name] = array();
            $result = $this->validateArray($data[$name], $relatedMeta, $relatedMeta);
            // 返回错误信息
            if (true !== $result) {
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
     * @todo 语言为可选
     */
    public function getJQueryValidateCode($meta, $relatedName = false)
    {
        $lang = Qwin::run('-lang');
        $validation = array(
            'rules' => array(),
            'messages' => array(),
        );
        foreach ($meta['field'] as $name => $field) {
            if (empty($field['validator']['rule'])) {
                continue;
            }
            foreach($field['validator']['rule'] as $rule => $param) {
                if ($relatedName) {
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
        foreach ($meta['field'] as $field) {
            if ($field['attr']['isReadonly']) {
                unset($data[$field['form']['name']]);
            }
        }
        foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
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
        foreach ($modelList as $model) {
            if ('db' == $model['type']) {
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
        foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
            $primaryKey = $relatedMeta['db']['primaryKey'];
            if (isset($data[$name][$primaryKey])) {
                $data[$name][$primaryKey] = null;
                //unset($data[$name][$primaryKey]);
            }
        }
        return $data;
    }

    public function getUrlList($field, $url_data)
    {
        Qwin::run('-arr')->set($url_data);
        $data = array();
        foreach ($url_data as $key => $val) {
            if ('' != $val && isset($url_data[$field[$key]['form']['name']])) {
                $data[$field[$key]['form']['name']] = $field[$key]['form']['name'];
            }
        }
        return $data;
    }
    public function makeRequiredAtFront($rule)
    {
        // 将必填项放在数组第一位
        if (array_key_exists('required', $rule)) {
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
        if (false !== $pos) {
            $repalce = Qwin::run('-arr')->set($repalce);
            $search = array();
            $count = count($repalce);
            for ($i = 0;$i < $count; $i++) {
                $search[$i] = '{' . $i . '}';
            }
            $data = str_replace($search, $repalce, $data);
        }
        return $data;
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
        foreach ($data as  $row) {
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
        foreach ($fieldArr as $field) {
            // 隐藏域不占空间
            if (in_array($field['form']['_type'], $banType)) {
                continue;
            }
            // 初始化数组
            if (0 == $x) {
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
        foreach ($meta['model'] as $model) {
            if ('relatedDb' == $model['type']) {
                /*
                 * 检验是否要保存数据
                 */
                $method = 'isSave' . $model['alias'] . 'Data';
                if (!method_exists($ctrler, $method)
                    || false === call_user_func_array(
                        array($ctrler, $method),
                        array($data, $query)
                        )){
                    return false;
                }
                    
                $relatedData = array();
                foreach ($model['fieldMap'] as $localField => $foreignField) {
                    if (isset($data[$localField])) {
                        $relatedData[$foreignField] = $data[$localField];
                    } elseif (isset($_POST[$localField])) {
                        $relatedData[$foreignField] = $_POST[$localField];
                    } else {
                        $relatedData[$foreignField] = null;
                    }
                }
                $relatedDbMeta = Qwin_Metadata_Manager::get($model['metadata']);
                // TODO 补全其他转换方式,分离该过程
                $copyData = $relatedData;
                foreach ($relatedDbMeta['field'] as $name => $field) {
                    $methodName = str_replace(array('_', '-'), '', 'convertdb' .  $model['alias'] . $name);
                    if (method_exists($ctrler, $methodName)) {
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
                $relatedDbQuery = $meta->getQuery($model['set']);
                $ini = Qwin::run('-ini');
                $modelName = $ini->getClassName('Model', $model['set']);
                $relatedDbQuery = new $modelName;
                $relatedDbQuery->fromArray($relatedData);
                $relatedDbQuery->save();
            }
        }
    }
}