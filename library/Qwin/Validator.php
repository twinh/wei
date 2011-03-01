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
     * 验证方法列表
     *
     * @var array
     */
    protected $_methods = array();

    public function  __construct($callbacks = null)
    {
        foreach((array)$callbacks as $callback) {
            if (class_exists($callback) || is_object($call)) {
                foreach (get_class_methods($callback) as $method) {
                    $method = strtolower($method);
                    $this->_methods[$method] = array($callback, $method);
                }
            } elseif (function_exists($callback)) {
                
            } else {
                
            }
        }
        p($this);
    }

    public function setMethod($name, $callback)
    {
        if (is_callable($callback)) {
            $this->_methods[$name] = $callback;
            return $this;
        }
        require_once 'Qwin/Validator/Exception.php';
        throw new Qwin_Validator_Exception('The param 2 is not callable.');
    }

    /**
     * 添加一个验证类
     * @param string $className
     * @return object
     */
    public function add($className)
    {
        $this->_class[$className] = $className;
        return $this;
    }

    public function call($method, $param)
    {
        foreach($this->_class as $class)
        {
            $object = Qwin::call($class);
            if(method_exists($object, $method))
            {
                return call_user_func_array(
                    array($object, $method),
                    $param
                );
            }
        }
        return true;
    }

    public function valid()
    {
        return true;
    }
}
