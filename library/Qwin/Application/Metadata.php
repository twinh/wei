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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 * @todo        分离出验证类,转换类,Doctrine处理类等
 */

/**
 * @see Qwin_Metadata_Abstract
 */
require_once 'Qwin/Metadata/Abstract.php';

class Qwin_Application_Metadata extends Qwin_Metadata_Abstract
{
    /**
     * @var array $_sanitiseOption   数据处理的选项
     *
     *      -- view                 是否根据视图类关联模型的配置进行转换
     *                              提示,如果转换的数据作为表单的值显示,应该禁止改选项
     *
     *      -- null                 是否将NULL字符串转换为null类型
     *
     *      -- type                 是否进行强类型的转换,类型定义在['fieldName]['db']['type']
     *
     *      -- meta                 是否使用元数据的sanitise配置进行转换
     *
     *      -- sanitise             是否使用转换器进行转换
     *
     *      -- relatedMeta          是否转换关联的元数据
     */
    protected $_sanitiseOption = array(
        'view'          => true,
        'null'          => true,
        'type'          => true,
        'meta'          => true,
        'sanitise'      => true,
        'link'          => false,
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
    /*protected $_queryOption = array(
        'model'         => array(),
        'type'          => array(),
    );*/

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
            $config = Qwin::config();
            $adapter = $config['database']['mainAdapter'];
        }
        $this->_tablePrefix = $config['database']['adapter'][$adapter]['prefix'];
        return $this->_tablePrefix;
    }

    /**
     * 获取标准的类名
     *
     * @param string $addition 附加的字符串
     * @param array $asc 配置数组
     * @return string 类名
     */
    public function getClassName($addition, $asc)
    {
        if (!isset($asc['namespace'])) {
            if (!isset($this->config)) {
                $this->config = Qwin::call('-config');
            }
            $asc['namespace'] = $this->config['asc']['namespace'];
        }
        return $asc['namespace'] . '_' . $asc['module'] . '_' . $addition . '_' . $asc['controller'];
    }

    /**
     * 将元数据的域定义,数据表定义加入模型中
     *
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @param Doctrine_Record $model Doctrine对象
     * @return Qwin_Application_Metadata 当前对象
     */
    public function metadataToModel(Qwin_Metadata_Abstract $meta, Doctrine_Record $model)
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
     * @param array $asc 元数据配置
     * @return Doctrine_Query 查询对象
     */
    public function getQueryByAsc($asc, $type = array(), $name = array())
    {
        $metaClass  = $this->getClassName('Metadata', $asc);
        $modelClass = $this->getClassName('Model', $asc);
        $meta       = $this->_manager->get($metaClass);
        $model      = Qwin::call($modelClass);
        return $this->getQuery($meta, $model, $type, $name);
    }

    /**
     * 通过元数据配置,获取Doctrine的查询对象
     *
     * @param Qwin_Metadata_Abstract $meta 元数据配置
     * @param Doctrine_Record $model 模型对象
     * @return Doctrine_Query 查询对象
     * @todo 缓存查询对象,模型对象
     * @todo padb问题
     */
    public function getQuery(Qwin_Metadata_Abstract $meta, Doctrine_Record $model = null, $type = array(), $name = array())
    {
        // 未定义模型,则初始化关联模型
        if (null == $model) {
            $asc = $meta->getAscFromClass();
            $modelClass = $this->getClassName('Model', $asc);
            $model = Qwin::call($modelClass);
        } else {
            $modelClass = get_class($model);
        }

        $joinModel = array();
        !is_array($name) && $name = array($name);
        !is_array($type) && $type = array($type);
        // 取出要关联的模块
        if (!empty($name) || !empty($type)) {
            foreach ($meta['model'] as $modelName => $modelSet) {
                if (in_array($modelName, $name) || in_array($modelSet['type'], $type)) {
                    $joinModel[$modelName] = $modelName;
                }
            }
        }

        // 自身的转换
        $this->metadataToModel($meta, $model);
        $query = Doctrine_Query::create()->from($modelClass);

        // 增加默认查询
        if (!empty($meta['db']['defaultWhere'])) {
            $this->addWhereToQuery($query, $meta['db']['defaultWhere']);
        }

        // 关联模型的转换
        foreach ($joinModel as $joinModelName) {
            // 该模型的设置
            $modelSet = $meta['model'][$joinModelName];
            $modelName = $this->getClassName('Model', $modelSet['asc']);
            $relatedMetaObject = $this->_manager->get($this->getClassName('Metadata', $modelSet['asc']));
            $relatedModelObejct = Qwin::call($modelName);
            $this->metadataToModel($relatedMetaObject, $relatedModelObejct);

            // 设置模型关系
            call_user_func(
                array($model, 'hasOne'),
                $modelName . ' as ' . $modelSet['alias'],
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
     * @param Doctrine_Query $query
     * @return object 当前对象
     * @todo 是否要将主类加入到$meta['model']数组中,减少代码重复
     */
    public function addSelectToQuery(Doctrine_Query $query)
    {
        /**
         * 设置主类的查询语句
         */
        $meta = $this;
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
            $linkedMetaObj = $this->_manager->get($this->getClassName('Metadata', $model['asc']));

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

    public function getPrimaryKeyName(array $asc)
    {
        $metadataName = $this->getClassName('Metadata', $asc);
        if (!class_exists($metadataName)) {
            return null;
        }
        $meta = $this->_manager->get($metadataName);
        return $meta['db']['primaryKey'];
    }

    /**
     * 根据应用结构配置获取元数据
     *
     * @param array $asc 应用结构配置
     * @return Application_Metadata
     */
    public function getMetadataByAsc($asc)
    {
        $metadataName = $this->getClassName('Metadata', $asc);
        if (class_exists($metadataName)) {
            $meta = $this->_manager->get($metadataName);
        } else {
            $metadataName = 'Application_Metadata';
            $meta = Qwin::call($metadataName);
        }
        Qwin::set('-meta', $metadataName);
        return $meta;
    }

    /**
     * 为Doctrine查询对象增加排序语句
     * 
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return object 当前对象
     * @todo 关联元数据的排序
     */
    public function addOrderToQuery(Doctrine_Query $query, array $addition = null)
    {
        $meta = $this;
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
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return object 当前对象
     * @todo 完善查询类型
     * @todo 复杂查询
     */
    public function addWhereToQuery(Doctrine_Query $query, array $addition = null)
    {
        $meta = $this;
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
     * @param Qwin_Metadata_Abstract $meta
     * @param Doctrine_Query $query
     * @param int|null $addition 附加的偏移配置
     * @return object 当前对象
     */
    public function addOffsetToQuery(Doctrine_Query $query, $addition = null)
    {
        $meta = $this;
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
     * @return object 当前对象
     */
    public function addLimitToQuery(Doctrine_Query $query, $addition = null)
    {
        $meta = $this;
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
     * 处理数据
     *
     * @param array $data 处理的数据
     * @param array $action 处理的行为,如db,list,view
     * @param array $option 配置选项
     * @return array
     */
    public function sanitise($data, $action = 'db', array $option = array(), array $dataCopy = array())
    {
        $option = array_merge($this->_sanitiseOption, $option);
        empty($dataCopy) && $dataCopy = $data;
        $action = strtolower($action);

        // 加载流程处理对象
        if ($option['meta']) {
            $flow = Qwin::call('Qwin_Flow');
        }

        /*if ($option['view']) {
            foreach ($meta['model'] as $model) {
                if ('view' == $model['type']) {
                    foreach ($model['fieldMap'] as $localField => $foreignField) {
                        !isset($data[$model['alias']][$foreignField]) && $data[$model['alias']][$foreignField] = '';
                        $data[$localField] = $data[$model['alias']][$foreignField];
                    }
                }
            }
        }*/

        foreach ($data as $name => $value) {
            if (!isset($this->field[$name])) {
                continue;
            }

            // 空转换 如果不存在,则设为空
            if ('NULL' === $data[$name] || '' === $data[$name]) {
                $data[$name] = null;
            }

            // 类型转换
            /*if ($option['type'] && $field['db']['type']) {
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
            }*/

            // 根据元数据中转换器的配置进行转换
            if ($option['meta'] && isset($this->field[$name]['sanitiser'][$action])) {
                $data[$name] = $flow->call(array($this->field[$name]['sanitiser'][$action]), $value);
            }

            // 使用转换器中的方法进行转换
            if ($option['sanitise']) {
                $method = str_replace(array('_', '-'), '', 'sanitise' . $action . $name);
                if (method_exists($this, $method)) {
                    $data[$name] = call_user_func_array(
                        array($this, $method),
                        array($value, $name, $data, $dataCopy)
                    );
                }
            }

            // 转换链接
            if ($option['link'] && 1 == $this->field[$name]['attr']['isLink'] && method_exists($this, 'setIsLink')) {
                $data[$name] = call_user_func_array(
                    array($this, 'setIsLink'),
                    array($value, $name, $data, $dataCopy, $action)
                );
            }
        }

        // 对db类型的关联元数据进行转换
        if ($option['relatedMeta']) {
            foreach ($this->getModelMetadataByType('db') as $name => $relatedMeta) {
                !isset($data[$name]) && $data[$name] = array();
                // 不继续转换关联元数据
                $option['relatedMeta'] = false;
                $data[$name] = $relatedMeta->sanitise($data[$name], $action, $option);
            }
        }

        return $data;
    }

    public function getModelMetadataByType($type = 'db')
    {
        if (empty($this['model'])) {
            return array();
        }
        $result = array();
        foreach ($this['model'] as $name => $model) {
            if ($model['enabled'] && $type == $model['type']) {
                if (!isset($model['metadata'])) {
                    $metadataClass = $this->getClassName('Metadata', $model['asc']);
                    $model['metadata'] = $this->_manager->get($metadataClass);
                }
                $result[$name] = $model['metadata'];
            }
        }
        return $result;
    }

    public function getModelMetadataByAlias($meta, $name)
    {
        if (isset($meta['model'][$name]['metadata'])) {
            return $meta['model'][$name]['metadata'];
        }
        $class = $this->getClassName('Metadata', $meta['model'][$name]['asc']);
        return $meta['model'][$name]['metadata'] = $this->_manager->get($class);
    }

    /**
     * 验证未通过的域信息
     * @var array
     * @example $this->_invalidData = array(
     *              'field' => array(
     *                  'rule1' => 'message1',
     *                  'rule2' => 'message2',
     *              )
     *          );
     */
    protected $_invalidData = array();
    
    /**
     * 设置通过元数据方法验证失败时的提示信息
     *
     * @param string $field 域
     * @param string $message 提示信息
     * @return Qwin_Application_Metadata 当前对象
     */
    public function setInvalidMetaMessage($field, $message)
    {
        $this->_invalidData[$field]['validate' . $field] = $message;
        return $this;
    }

    /**
     * 获取验证失败的域
     *
     * @return array
     */
    public function getInvalidFields()
    {
        return array_keys($this->_invalidData);
    }

    /**
     * 获取验证失败的信息
     *
     * @return array
     */
    public function getInvalidData()
    {
        return $this->_invalidData;
    }

    /**
     * 获取未通过域的提示信息
     *
     * @param Qwin_Application_Language $lang 语言对象
     * @param string $template 模板
     * @return string 
     */
    public function getInvalidMessage(Qwin_Application_Language $lang, $template = null)
    {
        $msssage = '';
        foreach ($this->_invalidData as $field => $row) {
            foreach ($row as $rule => $message) {
                $msssage .= $lang[$message] . PHP_EOL;
            }
        }
        return $msssage;
    }

    /**
     * 获取验证失败的规则
     *
     * @param string $field 域
     * @return array
     */
    public function getInvalidRulesByField($field)
    {
        if (isset($this->_invalidData[$field])) {
            return array_keys($this->_invalidData[$field]);
        }
        return array();
    }

    /**
     * 验证数据
     *
     * @param array $data 数据数组,键名表示域的名称,值表示域的值
     * @param array $option 配置选修
     * @return boolen
     */
    public function validate(array $data, array $option = array())
    {
        $option = array_merge($this->_validateOption, $option);

        // 重置验证不通过的域
        $this->_invalidData = array();

        // 加载验证对象
        if ($option['validator']) {
            $validator = Qwin::call('Qwin_Validator');
        }

        foreach ($data as $name => $value) {
            // 跳过不存在的域
            if (!isset($this->field[$name])) {
                continue;
            }

            // 根据验证对象进行验证
            if ($option['validator'] && !empty($this->field[$name]['validator']['rule'])) {
                $validateData = $this->field[$name]['validator'];
                foreach ($validateData['rule'] as $rule => $param) {
                    if (false === $validator->valid($rule, $param)) {
                        if (!isset($validateData['message'][$rule])) {
                            $this->_invalidData[$name][$rule] = 'VALIDATE_' . strtoupper($rule);
                        }
                        return false;
                    }
                }
            }

            // 根据元数据进行验证
            if ($option['meta']) {
                $method = 'validate' . str_replace(array('_', '-'), '', $name);
                if (method_exists($this, $method)) {
                    if (false === call_user_func_array(
                        array($this, $method),
                        array($value, $name, $data)
                    )) {
                        if (!isset($this->_invalidData[$name][$method])) {
                            $this->_invalidData[$name][$method] = 'VALIDATE_' . strtoupper($method);
                        }
                        return false;
                    }
                }
            }
        }

        // 验证关联域
        /*foreach ($this->getModelMetadataByType($meta, 'db') as $name => $relatedMeta) {
            !isset($data[$name]) && $data[$name] = array();
            $result = $this->validateArray($data[$name], $relatedMeta, $relatedMeta);
            // 返回错误信息
            if (true !== $result) {
                $result->field = array($name, $result->field);
                return $result;
            }
        }*/

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
        $lang = Qwin::call('-lang');
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
            $relatedMeta = $this->_manager->get($model['metadata']);
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
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @return array
     */
    public function deleteReadonlyValue($data, Qwin_Metadata_Abstract $meta)
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
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @return array
     */
    public function unsetPrimaryKeyValue($data, Qwin_Metadata_Abstract $meta)
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
    /*public function format($data, $repalce)
    {
        $repalce = (array)$repalce;
        $pos = strpos($data, '{0}');
        if (false !== $pos) {
            $search = array();
            $count = count($repalce);
            for ($i = 0;$i < $count; $i++) {
                $search[$i] = '{' . $i . '}';
            }
            $data = str_replace($search, $repalce, $data);
        }
        return $data;
    }*/

    /*public function saveRelatedDbData($meta, $data, $query)
    {
        $ctrler = Qwin::call('-controller');
        foreach ($meta['model'] as $model) {
            if ('relatedDb' == $model['type']) {
                
                 // 检验是否要保存数据
                 
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
                $relatedDbMeta = $this->_manager->get($model['metadata']);
                // TODO 补全其他转换方式,分离该过程
                $copyData = $relatedData;
                foreach ($relatedDbMeta['field'] as $name => $field) {
                    $methodName = str_replace(array('_', '-'), '', 'filterdb' .  $model['alias'] . $name);
                    if (method_exists($ctrler, $methodName)) {
                        !isset($relatedData[$name]) && $relatedData[$name] = null;
                        $relatedData[$name] = call_user_func_array(
                            array($ctrler, $methodName),
                            array($relatedData[$name], $name, $relatedData, $copyData)
                        );
                    }
                }

                // 保存数据
                $relatedDbQuery = $meta->getQuery($model['set']);
                $ini = Qwin::call('-ini');
                $modelName = $ini->getClassName('Model', $model['set']);
                $relatedDbQuery = new $modelName;
                $relatedDbQuery->fromArray($relatedData);
                $relatedDbQuery->save();
            }
        }
    }*/

    public function getRelatedListConfig($meta)
    {
        
    }

    /**
     * 过滤编辑数据,即改动过的,需要更新的数据
     *
     * @param array $data 从数据库取出的数据
     * @param array $post 用户提交的数据
     * @return array 
     */
    public function filterEditData($data, $post)
    {
        $result = array();
        foreach ($this->field as $name => $field) {
            if (
                isset($post[$name])
                && $post[$name] != $data[$name]
                && 1 != $field['attr']['isReadonly']
            ) {
                $result[$name] = $post[$name];
            }
        }
        return $result;
    }

    public function filterListData()
    {
        $result = array();
        foreach ($this['field'] as $name => $field) {
            if (1 == $field['attr']['isList']) {
                $result[$name] = true;
            }
        }
        return $result;
    }

    public function filterAddData($post)
    {
        $result = array();
        foreach ($this['field'] as $name => $field) {
            $result[$name] = isset($post[$name]) ? $post[$name] : null;
        }
        return $result;
    }
}
