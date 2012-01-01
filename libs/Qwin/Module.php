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
 * Module
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-03-22 15:36:41
 * @todo        对模块名称进行更全面的检查
 */
class Qwin_Module extends Qwin_Widget implements ArrayAccess
{
    /**
     * @var array           模块数据
     *
     *      -- source       模块标识源字符串
     *
     *      -- url          Url形式,即小写,横杆
     *
     *      -- path         路径形式,即首字母大写,斜杠
     *
     *      -- class        类名形式,即首字母大写,下划线
     *
     *      -- id           编号形式,即小写,横杠
     */
    protected $_data = array(
        'url'       => null,
        'path'      => null,
        'class'     => null,
        'id'        => null,
        'lang'      => null,
    );

    /**
     * 模块数组数据
     * @var array 
     */
    protected $_source = array();
    
    /**
     * 模块标识
     * @var string
     */
    protected $_string;
    
    /**
     * 替换的标识
     * 
     * @var array
     */
    protected static $_replace = array(
        '-' => '/',
        '_' => '/',
    );

    /**
     * 存放实例的数组
     * @var array
     */
    public static $_intances = array();
    
    public function __construct($source = null)
    {
        //$this->_string = (string)$source;
        $source = explode('/', $source);
        foreach ($source as &$value) {
            $value = explode('-', $value);
        }
        $this->_source = $source;
        //$this->_source = preg_split('/([^A-Za-z0-9])/', $source);
    }

    /**
     * 获取/初始化一个模块类
     * 
     * @param string $name 模块名称
     * @return Qwin_Module 
     */
    public function call($name = null)
    {
        // 空则返回第一个模块
        if (!$name && !empty(self::$_intances)) {
            return current(self::$_intances);
        }
        
        !$name && $name = $this->source;
        if (isset(self::$_intances[$name])) {
            return self::$_intances[$name];
        }
        return self::$_intances[$name] = new self($name);
    }
    
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * 获取模块编号形式名称
     *
     * @return string
     */
    public function toId()
    {
        if (empty($this->_data['id'])) {
            $sources = array();
            foreach ($this->_source as $source) {
                $sources[] = implode('', $source);
            }
            $this->_data['id'] = strtolower(implode('-', $sources));
            //$this->_data['id'] = strtolower(implode('-', $this->_source));
        }
        return $this->_data['id'];
    }

    /**
     * 获取模块路径形式名称
     *
     * @return string
     */
    public function toPath()
    {
        if (empty($this->_data['path'])) {
            $sources = array();
            foreach ($this->_source as $source) {
                $sources[] = implode('', array_map('ucfirst', $source));
            }
            $this->_data['path'] = implode('/', $sources) . '/';
            //$this->_data['path'] = implode('/', array_map('ucfirst', $this->_source)) . '/';
        }
        return $this->_data['path'];
    }

    /**
     * 获取模块Url形式名称
     *
     * @return string
     */
    public function toUrl()
    {
        if (empty($this->_data['url'])) {
            $sources = array();
            foreach ($this->_source as $source) {
                $sources[] = implode('-', $source);
            }
            $this->_data['url'] = strtolower(implode('/', $sources));
            //$this->_data['url'] = strtolower(implode('/', $this->_source));
        }
        return $this->_data['url'];
    }

    /**
     * 获取模块类名化名称
     *
     * @return string
     */
    public function toClass()
    {
        if (empty($this->_data['class'])) {
            $sources = array();
            foreach ($this->_source as $source) {
                $sources[] = implode('', array_map('ucfirst', $source));
            }
            $this->_data['class'] = implode('_', $sources);
            //$this->_data['class'] = implode('_', array_map('ucfirst', $this->_source));
        }
        return $this->_data['class'];
    }

    /**
     * 获取模块字符串名称
     * 
     * @return string 
     */
    public function toString()
    {
        if (!$this->_string) {
            $sources = array();
            foreach ($this->_source as $source) {
                $sources[] = implode('', array_map('ucfirst', $source));
            }
            $this->_string = implode(' ', $sources);
        }
        return $this->_string;
    }
    
    public function toLang()
    {
        if (!$this->_data['lang']) {
            end($this->_source);
            $this->_data['lang'] = ucfirst(implode('', $this->_source[key($this->_source)]));
            reset($this->_source);
        }
        return $this->_data['lang'];
    }
    
    public function getParent()
    {
        if (1 == count($this->_source)) {
            return false;
        }
        return $this->module(implode('-', $this->_source[0]));
    }

    /**
     * 检查是否存在索引
     *
     * @param string $offset 索引
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_data);
    }

    /**
     * 为索引设置值
     *
     * @param string $offset 索引
     * @param mixed $value 值
     * @return mixed $value 值
     */
    public function offsetSet($offset, $value)
    {
        return $this->_data[$offset] = $value;
    }

    /**
     * 删除一个索引
     *
     * @param string $offset 索引
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->_data)) {
            unset($this->_data[$offset]);
        }
    }

    /**
     * 获取索引的值
     *
     * @param string $offset 索引
     * @return mixed 值
     */
    public function offsetGet($offset)
    {
        $method = 'to' . $offset;
        if (method_exists($this, $method)) {
            return call_user_func(array($this, $method));
        }
        if (array_key_exists($offset, $this->_data)) {
            return $this->_data[$offset];
        }
        return null;
    }
}
