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
 * Request
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-02-13 23:02
 * @todo        缩减重复代码
 * @todo        通过Qwin_Request获取的值,为原生态,通过Qwin_Get等获取的值为对象?
 */
class Qwin_Request extends Qwin_Widget implements ArrayAccess
{
    /**
     * @var array           默认选项
     *
     *  -- get              注入$_GET的参数数组
     *
     *  -- post             注入$_POST的参数数组
     *
     *  -- cookie           注入$_COOKIE的参数数组
     *
     *  -- request          注入$_REQUEST的参数数组
     */
    public $options = array(
        'get'       => array(),
        'post'      => array(),
        'cookie'    => array(),
        'request'   => array(),
    );

    /**
     * 存储$_GET的值和用户自定义的get值
     * @var array
     */
    protected $_get = array();

    /**
     * 存储$_POST的值和用户自定义的post值
     * @var array
     */
    protected $_post = array();

    /**
     * 存储$_REQUEST的值和用户自定义的request值
     * @var array
     */
    protected $_request = array();

    /**
     * 存储$_COOKIE的值和用户自定义的cookie值
     * @var array
     */
    protected $_cookie = array();

    public function __construct(array $options = null)
    {
        

        // 关闭魔术引用
        ini_set('magic_quotes_runtime', 0);

        // 通过创建新的变量,而不是直接修改,不污染全局变量
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->_request = $_REQUEST;

        // 根据配置注入各变量
        !empty($options['get']) && $this->addGet($options['get']);
        !empty($options['post']) && $this->addPost($options['post']);
        !empty($options['cookie']) && $this->addCookie($options['cookie']);
        !empty($options['request']) && $this->addRequest($options['request']);
    }

    /**
     * 获取$_GET中数组中的值
     *
     * @param string|array $name 名称
     * @return string|array
     * @todo 是否应该有第二个参数,例如默认值,长度,回调函数
     */
    public function get($name)
    {
        if (!is_array($name)) {
            return isset($this->_get[$name]) ? $this->_get[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($this->_get[$key]) ? $this->_get[$key] : null;
        }
        return $array;
    }

    /**
     * 注入get变量
     *
     * @param string|array $name 名称或数组
     * @param mixed $value 值
     * @return Qwin_Request 当前对象
     */
    public function addGet($name, $value = null)
    {
        if (is_array($name)) {
            $this->_get += $name;
            $this->_request += $name;
        } else {
            $this->_get[$name] = $value;
            $this->_request[$name] = $value;
        }
        return $this;
    }

    public function getGet()
    {
        return $this->_get;
    }
    
    /**
     * 获取post参数
     * @todo getParam('post') ?
     */
    public function getPost()
    {
        return $this->_post;
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
            return isset($this->_post[$name]) ? $this->_post[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($this->_post[$key]) ? $this->_post[$key] : null;
        }
        return $array;
    }

    /**
     * 注入post变量
     *
     * @param string|array $name 名称或数组
     * @param mixed $value 值
     * @return Qwin_Request 当前对象
     */
    public function addPost($name, $value = null)
    {
        if (is_array($name)) {
            $this->_post += $name;
            $this->_request += $name;
        } else {
            $this->_post[$name] = $value;
            $this->_request[$name] = $value;
        }
        return $this;
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
            return isset($this->_cookie[$name]) ? $this->_cookie[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($this->_cookie[$key]) ? $this->_cookie[$key] : null;
        }
        return $array;
    }

    /**
     * 注入cookie变量
     *
     * @param string|array $name 名称或数组
     * @param mixed $value 值
     * @return Qwin_Request 当前对象
     */
    public function addCookie($name, $value = null)
    {
        if (is_array($name)) {
            $this->_cookie += $name;
            $this->_request += $name;
        } else {
            $this->_cookie[$name] = $value;
            $this->_request[$name] = $value;
        }
        return $this;
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
            return isset($this->_request[$name]) ? $this->_request[$name] : null;
        }
        foreach($name as $key) {
            $array[$key] = isset($this->_request[$key]) ? $this->_request[$key] : null;
        }
        return $array;
    }

    /**
     * 注入request变量
     *
     * @param string|array $name 名称或数组
     * @param mixed $value 值
     * @return Qwin_Request 当前对象
     */
    public function addRequest($name, $value = null)
    {
        if (is_array($name)) {
            $this->_request += $name;
        } else {
            $this->_request[$name] = $value;
        }
        return $this;
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
        return isset($this->_request[$offset]);
    }

    /**
     * 获取索引的数据
     *
     * @param string $offset 索引
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_request[$offset]) ? $this->_request[$offset] : null;
    }

    /**
     * 设置索引的值
     *
     * @param string $offset 索引
     * @param mixed $value 值
     */
    public function offsetSet($offset, $value)
    {
        $this->_request[$offset] = $value;
    }

    /**
     * 销毁一个索引
     *
     * @param string $offset 索引的名称
     */
    public function offsetUnset($offset)
    {
        unset($this->_request[$offset]);
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

    /**
     * 是否请求以Json数据显示
     *
     * @return bool
     */
    public function isJson()
    {
        return (bool)$this->get('json');
    }
}
