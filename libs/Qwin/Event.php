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
 * Event
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-23 17:19:36
 */
class Qwin_Event extends Qwin_Widget
{
    /**
     * Events data
     *
     * @var array
     */
    public $_events = array();

    /**
     * The type of event
     *
     * @var string
     */
    protected $_type;

    /**
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $_timeStamp;

    /**
     * Creat a new event object
     *
     * @param string $type
     */
    public function __construct($type = null)
    {
        $this->_type = (string)$type;
        $this->_timeStamp = microtime(true);
    }

    /**
     * Get the type of event
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get the time stamp
     *
     * @return string
     */
    public function getTimeStamp()
    {
        return $this->_timeStamp;
    }

    /**
     * 调用一个事件
     *
     * @param string $name 事件名称
     * @param mixed $params 参数
     * @return Qwin_Event|false 当前对象或中断
     */
    public function call($name, $params)
    {
        $name = strtolower($name);
        if (!isset($this->_events[$name])) {
            return $this;
        }

        // creat new event object
        $event = new self($name);

        // prepend $event object to the beginning of the params
        array_unshift($params, $event);

        foreach ($this->_events[$name] as $event) {
            if (false === $this->callback($event['callback'], $params)) {
                return false;
            }
        }

        return $this;
    }

    /**
     * 绑定事件
     *
     * @param string $name 事件名称
     * @param mixed $callback 回调结构
     * @param int $priority 执行优先级,越小越前执行
     * @return Qwin_Event 当前对象
     */
    public function add($name, $callback, $priority = 10)
    {
        if (!$this->isCallable($callback)) {
            $this->error('Parameter 2 should be a valid callback');
        }

        $name = strtolower($name);

        if (!isset($this->_events[$name])) {
            $this->_events[$name] = array();
        }

        // 预存储最大优先级的值 ?
        while (isset($this->_events[$name][$priority])) {
            $priority++;
        }

        $this->_events[$name][$priority] = array(
            'callback' => $callback,
        );

        // TODO 调用时才排序,或是实现不排序方法
        // 根据优先级排序
        ksort($this->_events[$name]);

        return $this;
    }

    /**
     * 移除一项或全部事件
     *
     * @param string|null $name 事件名称
     * @return Qwin_Event 当前对象
     */
    public function remove($name = null)
    {
        if (null === $name) {
            $this->_events = array();
        } else {
            $name = strtolower($name);
            if (isset($this->_events[$name])) {
                unset($this->_events[$name]);
            }
        }

        return $this;
    }
}
