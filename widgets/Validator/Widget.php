<?php
/**
 * widget
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
 * @since       2011-03-02 16:02:23
 */

class Validator_Widget extends Qwin_Widget
{
    /**
     * @var array $_options          数据验证的选项
     *
     *      -- break                当验证失败时,是否继续验证
     */
    public $options = array(
        'validate'      => true,
        'methods'       => 'Validator_Methods',
        'break'         => false,
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
     * 初始化对象,同时加载语言
     * 
     * @param array $options 选项
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->_lang->appendByWidget($this);
    }

    /**
     * 验证数据
     *
     * @param array $data 数据数组,键名表示域的名称,值表示域的值
     * @param array $options 配置选修
     * @return boolen
     * @todo 可自行抛出错误
     */
    public function valid(Qwin_Meta_Validation $validation, array $data, array $options = array())
    {
        // 合并选项
        $options = $this->_options = $options + $this->_options;
        
        // 调用钩子方法 TODO $this->_hook->preValidate();
        //$meta->preValidate();

        // 重置验证不通过的域
        $this->_invalidData = array();

        // 初始化验证结果为通过
        $result = true;

        // 为获取错误域信息做备份
        $this->_validation = $validation;

        // 加载验证对象
        $validator = Qwin::call('-validator');
        
        // 获取父元数据
        $meta = $validation->getParent();

        // TODO 允许定义只验证哪些字段
        // 根据字段验证
        foreach ($validation['fields'] as $name => $field) {
            if (!array_key_exists($name, $data)) {
                $data[$name] = null;
            } elseif ('' === $data[$name]) {
                $data[$name] = null;
            }
            
            // 如果该域不是必填的,且为空,则不验证内容
            if (!isset($field['rules']['required']) && is_null($data[$name])) {
                continue;
            }

            foreach ($field['rules'] as $rule => $param) {
                if (false === $validator->valid($rule, $data[$name], $param)) {
                    if (!isset($validateData['message'][$rule])) {
                        $this->_invalidData[$name][$rule] = 'VLD_' . strtoupper($rule);
                    } else {
                        $this->_invalidData[$name][$rule] = $validateData['message'][$rule];
                    }
                    if ($options['break']) {
                        return false;
                    } else {
                        $result = false;
                    }
                }
            }
            
            // 根据元数据进行验证
            if ($options['validate']) {
                $method = 'validate' . str_replace(array('_', '-'), '', $name);
                if (method_exists($meta, $method)) {
                    if (false === call_user_func_array(
                        array($meta, $method),
                        array($data[$name], $name, $data, $this)
                    )) {
                        if (!isset($this->_invalidData[$name][$method])) {
                            $this->_invalidData[$name][$method] = 'VLD_' . strtoupper($method);
                        }
                        if ($options['break']) {
                            return false;
                        } else {
                            $result = false;
                        }
                    }
                }
            }
        }

        // 调用钩子方法
        //$parentMeta->postValidate();

        return $result;
        // 验证关联域
        /*foreach ($this->getModelMetaByType($meta, 'db') as $name => $relatedMeta) {
            !isset($data[$name]) && $data[$name] = array();
            $result = $this->validateArray($data[$name], $relatedMeta, $relatedMeta);
            // 返回错误信息
            if (true !== $result) {
                $result->field = array($name, $result->field);
                return $result;
            }
        }*/
    }

    /**
     * 设置通过元数据方法验证失败时的提示信息
     *
     * @param string $field 域
     * @param string $message 提示信息
     * @return Qwin_Application_Meta 当前对象
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
     * @return string
     */
    public function getInvalidMessage()
    {
        $msssage = '';
        $lang = $this->_lang;
        $result = array(
            'title' => $lang['VLD_DATA'],
            'content' => array(),
        );
        foreach ($this->_invalidData as $field => $row) {
            foreach ($row as $rule => $message) {
                $result['content'][] = $lang->f($field) . ':' . $lang[$message] . PHP_EOL;
            }
        }
        return $result;
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
}
