<?php
/**
 * Language
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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 18:41:13
 */

class Qwin_Application_Language implements ArrayAccess
{
    /**
     * 语言转换数据
     * @var array
     */
    protected $_data = array();

    /**
     * 当前语言名称
     *
     * @var string
     */
    protected $_name;

    /**
     * 附加的文件列表
     *
     * @var array
     */
    protected $_appendedFileList = array();

    /**
     * 命名空间列表
     *
     * @var array
     * @see Qwin_Application_Manager ::getNamespaceList
     */
    protected $_namespaceList;

    /**
     * 当前命名空间所在的路径,用于定位语言文件
     *
     * @var string
     */
    protected $_namespacePath;

    /**
     * 初始化路径,加载数据
     */
    public function  __construct(array $asc = null)
    {
        if (null == $asc) {
            $asc = Qwin::config('asc');
        }
        $this->_namespaceList = Qwin_Application_Namespace::getList();
        $this->_namespacePath = $this->_namespaceList[$asc['namespace']];

        // 获取名称,同时加载当前命名空间的语言文件
        $this->_loadData();
        $this->appendByAsc($asc);
    }

    /**
     * 翻译一个字符串
     *
     * @param string $name
     * @return string|null
     */
    public function t($name = null)
    {
        return $this->offsetGet($name);
    }

    /**
     * 通过魔术方法设置一个属性的值
     *
     * @param string $name 名称
     * @param mixed $value 值
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * 通过魔术方法获取一个属性的值
     *
     * @param string $name 名称
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
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
        return isset($this->_data[$offset]) ? $this->_data[$offset] : $offset;
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

    /**
     * 返回数据数组
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * 附加数据数组
     *
     * @param array $data 数据数组
     * @return Qwin_Application_Language 当前对象
     */
    public function append(array $data)
    {
        $this->_data += $data;
        return $this;
    }

    /**
     * 附加文件数据
     *
     * @param string $file 文件路径
     * @example $file = $this->isExist('zh-CN');
     *          if (!$file) {
     *              $this->_appendFile($file);
     *          }
     */
    protected function _appendFile($file)
    {
        if (!isset($this->_appendedFileList[$file])) {
            $this->append((array)require $file);
            $this->_appendedFileList[$file] = true;
        }
        return $this;
    }
    
    /**
     * 获取当前的语言名称
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * 加载语言数据
     *
     * @return <type>
     */
    public function _loadData()
    {
        // 用户请求
        /* @var $request Common_Request */
        $request = Qwin::call('-request');
        $name = $request['lang'];
        $this->_name = &$name;
        if (null != $name && ($file = $this->isExist($name))) {
            $session['lang'] = $name;
            return $this->_appendFile($file);
        }

        // 会话中用户的配置
        /* @var $session Qwin_Session */
        $session = Qwin::call('-session');
        $name = $session['lang'];
        if (null != $name && ($file = $this->isExist($name))) {
            return $this->_appendFile($file);
        }

        // 全局配置
        $name = Qwin::config('language');
        if (null != $name && ($file = $this->isExist($name))) {
            $session['lang'] = $name;
            return $this->_appendFile($file);
        }

        throw new Qwin_Exception('Language file "' . $name . '.php" not found in path "'
                                . $this->_namespacePath . DIRECTORY_SEPARATOR . 'Language".' );
    }

    /**
     * 根据应用结构配置加载语言
     *
     * @param array $asc 应用结构配置
     * @return Qwin_Application_Language|false 当前对象|失败
     */
    public function appendByAsc(array $asc)
    {
        if (isset($this->_namespaceList[$asc['namespace']])) {
            $file = $this->_namespaceList[$asc['namespace']] . '/' . $asc['module'] . '/Language/' . $this->_name . '.php';
            if (is_file($file)) {
                return $this->_appendFile($file);
            }
        }
        return false;
    }

    /**
     * 根据命名空间加载语言
     *
     * @param string $namespace
     * @return Qwin_Application_Language|false 当前对象|失败
     */
    public function appendByNamespace($namespace)
    {
        if (isset($this->_namespaceList[$namespace])) {
            $file = $this->_namespaceList[$namespace] . '/Language/' . $this->_name . '.php';
            if (is_file($file)) {
                return $this->_appendFile($file);
            }
        }
        return false;
    }

    /**
     * 根据文件路径加载语言
     *
     * @param string $namespace
     * @return Qwin_Application_Language|false 当前对象|失败
     */
    public function appendByFile($file)
    {
        if (is_file($file)) {
            return $this->_appendFile($file);
        }
        return false;
    }

    /**
     * 检查语言是否存在
     *
     * @param string $name 语言名称,标准格式
     * @return bool|string 语言存在时,返回路径,不存在时,返回false
     */
    public function isExist($name)
    {
        $file = $this->_namespacePath . '/Language/' . $name . '.php';
        if (is_file($file)) {
            return $file;
        }
        return false;
    }
}
