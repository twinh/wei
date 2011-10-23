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
 * Event
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-23 17:19:36
 */
class Qwin_Event extends Qwin_Widget
{
    /**
     * 存储事件数据
     * @var array
     */
    public $data;
    
    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->data = &$this->hook->events;
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
        if (!isset($this->data[$name])) {
            return $this;
        }

        foreach ($this->data[$name] as $event) {
            if (isset($event['file'])) {
                if (!is_file($event['file'])) {
                    continue;
                }

                require_once $event['file'];
                $callback = array($event['class'], 'trigger' . $name);
            } else {
                $callback = $event['callback'];
            }

            // 如果返回false,终止继续调用事件
            if (false === $this->callback($callback, $params)) {
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
        $name = strtolower($name);
        
        if (!isset($this->data[$name])) {
            $this->data[$name] = array();
        }
        
        // 预存储最大优先级的值 ?
        while (isset($this->data[$name][$priority])) {
            $priority++;
        }
        
        $this->data[$name][$priority] = array(
            'callback' => $callback,
        );
        
        // TODO 调用时才排序,或是实现不排序方法
        // 根据优先级排序
        ksort($this->data[$name]);

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
            $this->data = array();
        } else {
            $name = strtolower($name);
            if (isset($this->data[$name])) {
                unset($this->data[$name]);
            }
        }
        
        return $this;
    }
}