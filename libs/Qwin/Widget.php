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
     * 调用者,对象环的上一个对象
     *
     * @var Qwin_Widget
     */
    public $__invoker;

    /**
     * Options
     *
     * @var array
     */
    public $options = array();

    /**
     * init widget
     *
     * @param mixed $options 对象的值
     * @return Qwin_Widget 当前对象
     */
    public function __construct(array $options = array())
    {
        $this->option($options);
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
     * @todo test reference in 5.2 version
     */
    public function option($name = null, $value = null)
    {
        // set options
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->option($k, $v);
            }
            $name = $this->options;
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
     * 魔术方法,实现通过方法调用同名微件
     *
     * @param string $name 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($name, $args)
    {
        return Qwin::getInstance()->invokeWidget($this, $name, $args);
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
        $widget->__invoker = $this;
        return $widget;
    }

    // should be implemented by subclasses
    //public function __invoke();
}