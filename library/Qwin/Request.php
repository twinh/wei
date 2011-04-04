<?php
/**
 * 请求处理
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
 * @subpackage  Request
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-13 23:02
 */

class Qwin_Request implements ArrayAccess
{
    public function  __construct()
    {
        $this->_data = &$_REQUEST;
    }

    /**
     * 获取$_GET中数组中的值
     *
     * @param string|array $name 名称
     * @return string|array
     */
    public function get($name)
    {
        if (!is_array($name)) {
            return isset($_GET[$name]) ? $_GET[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($_GET[$key]) ? $_GET[$key] : null;
        }
        return $array;
    }

    /**
     * 获取$_POST中数组中的值
     *
     * @param string|array $name 名称
     * @return string|array
     */
    public function post($name)
    {
        if (!is_array($name)) {
            return isset($_POST[$name]) ? $_POST[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($_POST[$key]) ? $_POST[$key] : null;
        }
        return $array;
    }

    /**
     * 获取$_COOKIE数组中的值
     *
     * @param string|array $name 名称
     * @return string|array
     */
    public function cookie($name)
    {
        if (!is_array($name)) {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        }
        return $array;
    }
    
    /**
     * 获取$_REQUEST数组中的值
     *
     * @param arary $arr 键名数组
     * @param array
     */
    public function request($name)
    {
        if (!is_array($name)) {
            return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        }
        return $array;
    }

    /**
     * 获取$_REQUEST数组中的值
     *
     * @param arary $arr 键名数组
     * @param array
     */
    public function server($name)
    {
        if (!is_array($name)) {
            return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
        }
        return $array;
    }

    /**
     * 请求方法是否为POST
     *
     * @return bool
     */
    public function isPost()
    {
        return 'POST' == $this->server('REQUEST_METHOD');
    }

    /**
     * 请求方法是否为GET
     *
     * @return bool
     */
    public function isGet()
    {
        return 'GET' == $this->server('REQUEST_METHOD');
    }

    /**
     * 检查索引是否存在
     *
     * @param string $offset 索引
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * 获取索引的数据
     *
     * @param string $offset 索引
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

    /**
     * 设置索引的值
     *
     * @param string $offset 索引
     * @param mixed $value 值
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    /**
     * 销毁一个索引
     *
     * @param string $offset 索引的名称
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    public function getModule()
    {
        return $this->get('module');
    }

    public function getAction()
    {
        $action = $this->get('action');
        return $action ? strtolower($action) : 'index';
    }

    /**
     * 是否是ajax请求
     *
     * @return bool
     * @todo 是否应该从server判断
     */
    public function isAjax()
    {
        return (bool)$this->get('ajax');
    }
}
