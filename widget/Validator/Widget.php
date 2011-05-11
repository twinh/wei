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

class Validator_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array $_options          数据验证的选项
     *
     *      -- meta                 是否根据元数据的验证配置进行转换
     *
     *      -- validator            是否根据验证器进行验证
     *
     *      -- break                当验证失败时,是否继续验证
     *
     */
    protected $_defaults = array(
        'meta'          => true,
        'validator'     => 'Qwin_Validator',
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
     * 验证数据
     *
     * @param array $data 数据数组,键名表示域的名称,值表示域的值
     * @param array $options 配置选修
     * @return boolen
     */
    public function valid(array $data, Qwin_Metadata_Abstract $meta, array $options = array())
    {
        // 调用钩子方法
        $meta->preValidate();

        $options = $options + $this->_options;

        // 重置验证不通过的域
        $this->_invalidData = array();

        // 初始化验证结果为通过
        $result = true;

        // 为获取错误域信息做备份
        $this->_meta = $meta;

        // 加载验证对象
        if ($options['validator']) {
            $validator = Qwin::call($options['validator']);
        }

        foreach ($data as $name => $value) {
            // 跳过不存在的域
            if (!isset($meta['fields'][$name])) {
                continue;
            }

            // 根据验证对象进行验证
            if ($options['validator'] && !empty($meta['fields'][$name]['validator']['rule'])) {
                $validateData = $meta['fields'][$name]['validator'];
                // 如果该域不是必填的,且为空,则不验证内容
                if (!isset($validateData['rule']['required']) && '' == $value) {
                    continue;
                }
                foreach ($validateData['rule'] as $rule => $param) {
                    if (false === $validator->valid($rule, $value, $param)) {
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
            }

            // 根据元数据进行验证
            if ($options['meta']) {
                $method = 'validate' . str_replace(array('_', '-'), '', $name);
                if (method_exists($meta, $method)) {
                    if (false === call_user_func_array(
                        array($meta, $method),
                        array($value, $name, $data, $this)
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
        $meta->postValidate();

        return $result;
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
    public function getInvalidMessage($template = null)
    {
        $msssage = '';
        $lang = Qwin::call('-lang');
        $this->loadLanguage();
        $result = array(
            'title' => $lang['VLD_DATA'],
            'content' => array(),
        );
        foreach ($this->_invalidData as $field => $row) {
            foreach ($row as $rule => $message) {
                $result['content'][] = $lang[$this->_meta['field'][$field]['basic']['title']] . ':' . $lang[$message] . PHP_EOL;
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
