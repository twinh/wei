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

class Qwin_Request
{
    /**
     * 获取 $_GET 数组中的值
     *
     * @param string $name url查询的字符串中的键名
     * @return mixed 键名对应的值
     */
    public function g($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }
    
    /**
     * 获取 $_POST 数组中的值
     * @param string $name $_POST 数组对应的键名
     * @return mixed 键名对应的值
     */
    public function p($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
    
    /**
     * 获取 $_REQUEST 数组中的值
     *
     * @param string $name $_REQUEST 数组对应的键名
     * @return mixed 键名对应的值
     */
    public function r($name)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
    }
    
    /**
     * 获取 $_COOKIE 数组中的值
     *
     * @param string $key $_COOKIE 数组对应的键名
     * @return mixed 键名对应的值
     */
    public function c($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    /**
     * 获取 $_GET 数组中的值
     *
     * @param arary $nameArr 键名数组
     * @param array
     */
    public function get(array $nameArr)
    {
        $arr = array();
        foreach ($nameArr as $name)
        {
            $arr[$name] = $this->g($name);
        }
        return $arr;
    }
    
    /**
     * 获取 $_POST 数组中的值
     *
     * @param arary $arr 键名数组
     * @param array
     */
    public function post(array $nameArr)
    {
        $arr = array();
        foreach ($nameArr as $name)
        {
            $arr[$name] = $this->p($name);
        }
        return $arr;
    }

    /**
     * 获取 $_REQUEST 数组中的值
     *
     * @param arary $arr 键名数组
     * @param array
     */
    public function request(array $nameArr)
    {
        $arr = array();
        foreach ($nameArr as $name)
        {
            $arr[$name] = $this->r($name);
        }
        return $arr;
    }

    /**
     * 获取 $_COOKIE 数组中的值
     *
     * @param arary $arr 键名数组
     * @param array
     */
    public function cookie(array $nameArr)
    {
        $arr = array();
        foreach ($nameArr as $name)
        {
            $arr[$name] = $this->c($name);
        }
        return $arr;
    }
}
