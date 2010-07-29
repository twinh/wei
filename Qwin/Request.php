<?php
/**
 * qinit 的名称
 *
 * qinit 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-02-13 23:02 utf-8 中文
 * @since     2010-02-13 23:02 utf-8 中文
 */

class Qwin_Request
{
        /**
     * 获取 url 查询字符串中的值,注意,如果使用了自定义的url分割符,那就不是 $_GET 中的键名和值
     * @param string $key url查询的字符串中的键名
     * @return mixed 键名对应的值
     */
    function g($key)
    {
        return qw('-url')->g($key);
    }
    
    /**
     * 获取 $_POST 数组中的值
     * @param string $key $_POST 数组对应的键名
     * @return mixed 键名对应的值
     */
    function p($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : NULL;
    }
    
    /**
     * 获取 $_REQUEST 数组中的值
     *
     * @param string $key $_REQUEST 数组对应的键名
     * @return mixed 键名对应的值
     */
    function r($key)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : NULL;
    }
    
    /**
     * 获取 $_COOKIE 数组中的值
     *
     * @param string $key $_COOKIE 数组对应的键名
     * @return mixed 键名对应的值
     */
    function c($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : NULL;
    }

    /**
     * 获取 $_POST 数组中的值
     *
     * @param arary $arr 键名数组
     * @param mixed
     */
    function get($arr)
    {
        return qw('-url')->get($arr);
    }
    
    /**
     * 获取 url 查询字符串中的值,注意,直接传入 $_GET 是不安全的
     *
     * @param arary $arr 键名数组
     * @param mixed
     */
    function post($arr)
    {
        $arr = qw('-arr')->set($arr);
        foreach ($arr as $key => $val)
        {
            $arr_2[$val] = self::p($val);
        }
        return $arr_2;
    }
    
    /**
     * 合并 $this->get $this->post,详情查看对应方法
     *
     * @param array $keys 键名
     * @param string $method 方法,在 get 和 post 中二选一
     * @param mixed
     */
    function gp($keys, $method = 'get')
    {
        $method = $method == 'get' ? 'get' : 'post';
        $this->method($keys);
    }
}
