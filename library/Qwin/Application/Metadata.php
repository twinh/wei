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
 * @todo        关联元数据是否能实现功能性
 */

/**
 * @see Qwin_Metadata_Abstract
 */
require_once 'Qwin/Metadata/Abstract.php';

abstract class Qwin_Application_Metadata extends Qwin_Metadata_Abstract
{
    /**
     * @var array $_sanitiseOption  数据处理的选项
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
     * 根据应用结构配置获取元数据对象
     *
     * @param array $asc 应用结构配置
     * @return Qwin_Metadata_Abstarct 元数据对象
     */
    public static function getByAsc(array $asc, $instanced = true)
    {
        $class = $asc['namespace'] . '_' . $asc['module'] . '_Metadata_' . $asc['controller'];
        return $instanced ? Qwin_Metadata::getInstance()->get($class) : $class;
    }

    /**
     *
     * @param array $asc
     * @return <type> 
     */
    public function getPrimaryKeyName(array $asc)
    {
        $metadataName = self::getByAsc($asc);
        if (!class_exists($metadataName)) {
            return null;
        }
        $meta = $this->_manager->get($metadataName);
        return $meta['db']['primaryKey'];
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

    /**
     * 根据类型获取模型的元数据
     *
     * @param string $type 类型
     * @return array 由元数据组成的数组
     */
    public function getModelMetadataByType($type = 'db')
    {
        if (empty($this['model'])) {
            return array();
        }
        $result = array();
        foreach ($this['model'] as $name => $model) {
            if ($model['enabled'] && $type == $model['type']) {
                if (!isset($model['metadata'])) {
                    $model['metadata'] = self::getByAsc($model['asc']);
                }
                $result[$name] = $model['metadata'];
            }
        }
        return $result;
    }

    /**
     * 根据别名获取模型的元数据
     *
     * @param string $name 别名
     * @return Qwin_Metadata_Abstract $meta 元数据
     */
    public function getModelMetadataByAlias($name)
    {
        if (isset($this['model'][$name]['metadata'])) {
            return $this['model'][$name]['metadata'];
        }
        return $meta['model'][$name]['metadata'] = self::getByAsc($meta['model'][$name]['asc']);
    }
    
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
                $msssage .= $lang[$this['field'][$field]['basic']['title']] . $lang[$message] . PHP_EOL;
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
    public function  validate(array $data, array $option = array())
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
                    if (false === $validator->valid($rule, $value, $param)) {
                        if (!isset($validateData['message'][$rule])) {
                            $this->_invalidData[$name][$rule] = 'VLD_' . strtoupper($rule);
                        } else {
                            $this->_invalidData[$name][$rule] = $validateData['message'][$rule];
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
}
