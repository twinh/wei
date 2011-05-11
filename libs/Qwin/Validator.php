<?php
/**
 * Validator
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
 * @subpackage  Validator
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-5-21 7:03:17
 */

class Qwin_Validator
{
    /**
     * 未处理的规则
     *
     * @var array
     */
    protected $_rawRules = array();

    /**
     * 处理后的规则
     *
     * @var array
     */
    protected $_rules = array();

    /**
     * 初始化
     *
     * @param array $callbacks
     * @return Qwin_Validator 当前对象
     */
    public function  __construct($callbacks = null)
    {
        if (isset($callbacks)) {
            $this->setRules((array)$callbacks);
        }
    }

    /**
     * 设置规则
     *
     * @param mixed $callback 回调结构,可能的类型
     *              ①类对象/类名 将类的所有公用方法作为验证规则
     *              ②函数名     如果不提供规则名,则函数名为规则名
     *              ③类方法数组  如果不提供规则名,则方法名为规则名
     * @param string $rule 规则名称,可选
     * @return Qwin_Validator 当前对象
     */
    public function setRule($callback, $rule = null)
    {
        if (!isset($rule)) {
            $this->_rawRules[] = $callback;
        } else {
            $this->_rawRules[$rule] = $callback;
        }
        return $this;
    }

    /**
     * 设置规则列表
     *
     * @param array $callbacks 数组,键名为规则名或留空,值为回调结构或类对象,类名
     * @return Qwin_Validator 当前对象
     */
    public function setRules(array $callbacks)
    {
        foreach ($callbacks as $rule => $callback) {
            $this->_rawRules[$rule] = $callback;
        }
        return $this;
    }

    /**
     * 处理规则
     *
     * @param bool $update 是否重新更新规则
     * @return Qwin_Validator 当前对象
     */
    protected function _processRules($update = false)
    {
        if (!$update && !empty($this->_rules)) {
            return $this;
        }
        foreach ($this->_rawRules as $rule => $callback) {
            if (class_exists($callback)) {
                $class  = $callback;
                $object = Qwin::call($class);
            } elseif (is_object($callback)) {
                $class  = get_class($callback);
                $object = $callback;
            } else {
                unset($class, $object);
            }
            if (isset($class)) {
                $reflection = new ReflectionClass($callback);
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    $name = strtolower($method->getName());
                    if ($method->isStatic()) {
                        $this->_rules[$name] = array($class, $name);
                    } else {
                        $this->_rules[$name] = array($object, $name);
                    }
                }
            } elseif (is_callable($callback)) {
                $this->_rules[$rule] = $callback;
            }
        }
        return $this;
    }

    /**
     * 验证
     *
     * @param string $rule 规则名称
     * @param mixed $value 验证的值
     * @param mixed $param 验证附加参数
     * @return bool 验证结果
     */
    public function valid($rule, $value, $param = null)
    {
        $this->_processRules();
        $rule = strtolower($rule);
        if (isset($this->_rules[$rule])) {
            return (bool)call_user_func($this->_rules[$rule], $value, $param);
        }
        require_once 'Qwin/Validator/Exception.php';
        throw new Qwin_Validator_Exception('Rule "' . $rule . '" not found.');
    }
}
