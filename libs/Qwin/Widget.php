<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @see Qwin_Widgetable
 */
require_once 'Widgetable.php';

/**
 * Widget
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-03 00:28:06
 */
class Qwin_Widget implements Qwin_Widgetable
{
    /**
     * 对象的值
     *
     * @var mixed
     */
    public $source;

    /**
     * 调用者,对象环的上一个对象
     *
     * @var Qwin_Widget
     */
    public $invoker;

    /**
     * options
     *
     * @var array
     */
    public $options = array();

    /**
     * init widget
     *
     * @param mixed $source 对象的值
     * @return Qwin_Widget 当前对象
     */
    public function __construct($source = null)
    {
        if ('Qwin_Widget' == get_class($this)) {
            $this->source = $source;
        } else {
            $this->option((array)$source);
        }
    }

    /**
     * Get or set options
     *
     * @param mixed $name option name or options array
     * @param mixed $value
     * @return mixed
     * @example $widget->option('name');            // get "name" option's value
     *          $widget->option('name', 'value');   // set "name" to "value"
     *          $widget->option();                  // get all options
     *          $widget->option(array());           // set options
     */
    public function option($name = null, $value = null)
    {
        // set options
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->option($k, $v);
            }
            return $this;
        }

        if (is_string($name) || is_int($name)) {
            // get option
            if (1 == func_num_args()) {
                $method = 'get' . ucfirst($name) . 'Option';
                if (method_exists($this, $method)) {
                    return $this->$method();
                } else {
                    return isset($this->options[$name]) ? $this->options[$name] : null;
                }
            // set option
            } else {
                $method = 'set' . ucfirst($name) . 'Option';
                if (method_exists($this, $method)) {
                    return $this->$method($value);
                } else {
                    return $this->options[$name] = $value;
                }
            }
        }

        // get all options
        if (null === $name) {
            return $this->options;
        }

        // not match any actions
        return null;
    }

    /**
     * Get/Set source value
     *
     * @param mixed $value source value
     * @return Qwin_Widget
     */
    public function source($value = null)
    {
        if (!func_num_args()) {
            return $this->source;
        } else {
            $this->source = $value;
            return $this;
        }
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
        return Qwin::getInstance()->callWidget($this, $name, $args);
    }

    /**
     * 魔术方法,实现通过对象属性获取同名微件
     *
     * @param string $name 微件名称
     * @return Qwin_Widget
     */
    public function __get($name)
    {
        $this->$name = $widget = Qwin::getInstance()->widget($name);
        $widget->invoker = $this;
        return $widget;
    }

    /**
     * Call the widget object as a function by called the "call" method
     *
     * @param mixed $arg
     * @return mixed
     */
    public function __invoke($arg = null)
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'call'), $args);
    }

    /**
     * 魔术方法,返回对象值的字符串形式
     *
     * @return string
     * @todo 针对不同类型处理
     */
    public function __toString()
    {
        return (string)$this->source;
    }
}