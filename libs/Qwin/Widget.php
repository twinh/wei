<?php
/**
 * Qwin Framework
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
 */

/**
 * Widget
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-03 00:28:06
 */
class Qwin_Widget
{
    /**
     * 对象的值
     * @var mixed 
     */
    public $source;
    
    /**
     * 调用者,对象环的上一个对象
     * @var mixed
     */
    public $invoker;

    /**
     * 选项
     * @var array
     */
    public $options = array();
    
    /**
     * 初始化微件
     * 
     * @param mixed $source 对象的值
     * @return Qwin_Widget 当前对象
     */
    public function __construct($source = null)
    {
        if ('Qwin_Widget' == get_class($this)) {
            $this->source = $source;
        } else {
            if (is_array($this->options)) {
                $this->options = (array)$source + (array)$this->options;
            } else {
                $this->options = $source;
            }
        }
    }
    
    /**
     * 获取/设置选项
     * 
     * @param mixed $name 选项名称
     * @return mixed 
     * @example $widget->option('name');            // 获取name选项
     *          $widget->option('name', 'value');   // 设置name选项的值为value  
     *          $widget->option();                  // 获取所有选项
     *          $widget->option(array());           // 设置所有选项      
     */
    public function option($name = null)
    {
        // 设置所有选项
        if (is_array($name)) {
            $name = $name + $this->options;
            $this->options = &$name;
            return $this;
        }
        
        // 获取/设置某一个选项
        if (is_string($name) || is_int($name)) {
            if (2 == func_num_args()) {
                return $this->options[$name] = func_get_arg(1);
            }
            return isset($this->options[$name]) ? $this->options[$name] : null;
        }
        
        // 获取所有选项
        if (null === $name ) {
            return $this->options;
        }

        // 不匹配任何操作
        return null;
    }

    /**
     * 魔术方法,实现通过方法调用同名微件
     * 
     * @param string $name 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($name, $args)
    {
        // 获取微件对象
        if (!isset($this->$name)) {
            $this->$name = Qwin::widget($name);
        }
        $widget = $this->$name;
        
        if (!method_exists($widget, 'call')) {
            require_once 'Qwin/Exception.php';
            throw new Qwin_Exception('Method "call" not found in widget "' . get_class($widget) . '"');
        }

        // 执行相应方法
        $widget->invoker = $this;
        $widget->source = $this->source;
        $result = call_user_func_array(array($widget, 'call'), $args);
        $this->source = $widget->source;
        return $result;
    }
    
    /**
     * 魔术方法,实现通过对象属性获取同名微件
     * 
     * @param string $name 微件名称
     * @return Qwin_Widget 
     */
    public function __get($name)
    {
        $this->$name = Qwin::widget($name);
        $this->$name->invoker = $this;
        return $this->$name;
    }
    
    public function __toString()
    {
        return (string)$this->source;
    }
}