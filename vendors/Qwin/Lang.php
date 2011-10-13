<?php
/**
 * Language
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
 * @package     Widget
 * @subpackage  Lang
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 18:41:13
 */

class Qwin_Lang extends Qwin_Widget implements ArrayAccess
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
    protected $_appendedFiles = array();

    /**
     * @var array           默认选项
     *
     *  -- rootModule       根模块,当模块找不到时,加载此模块的语言
     *
     *  -- module           要加载语言的模块
     *
     *  -- name             语言名称
     */
    public $options = array(
        'name'       => 'zh-CN',
        'path'       => 'lang/',
    );

    /**
     * 初始化语言微件
     * 
     * @param array $options 选项
     * @return Lang_Widget
     */
    public function  __construct(array $options = null)
    {
        parent::__construct($options);
        $options = &$this->options;
        
        /**
         * 注意:语言微件有自己的__get,__set方法,通过语言微件加载其他微件,应通过
         *     $this->_wiget->get('name'),而不能直接使用$tihs->_name取得.
         */
        // 设置应用目录和微件目录
        $options['appPaths'] = Qwin::widget('app')->option('paths');

        // 通过应用根目录检查语言是否存在

        /* @var $session Qwin_Session */
        $session = Qwin::widget('session');

        // 用户请求$request
        $name = $this->get('lang');
        $this->_name = &$name;
        foreach ($options['appPaths'] as $path) {
            $file = $path . $options['path'] . $name . '.php';
            if (null != $name && is_file($file)) {
                $session['lang'] = $name;
                return $this->_appendFile($file);
            }
        }
        
        // 会话配置
        $name = $session['lang'];
        foreach ($options['appPaths'] as $path) {
            $file = $path . $options['path'] . $name . '.php';
            if (null != $name && is_file($file)) {
                return $this->_appendFile($file);
            }
        }
        
        // 全局配置
        $name = $options['name'];
        foreach ($options['appPaths'] as $path) {
            $file = $path . $options['path'] . $name . '.php';
            if (null != $name && is_file($file)) {
                $session['lang'] = $name;
                return $this->_appendFile($file);
            }
        }
        
        throw new Qwin_Widget_Exception('Language file "' . $file . '" for "' . $name . '" not found.');
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
        if (!isset($this->_appendedFiles[$file])) {
            $this->appendData((array)require $file);
            $this->_appendedFiles[$file] = true;
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
     * 根据模块标识加载语言
     * 
     * @param string|Qwin_Module $module 模块标识
     * @return false|Lang_Widget
     * @todo 增加参数:递归加载和语言名称
     */
    public function appendByModule($module)
    {
        if (!$module instanceof Qwin_Module) {
            $module = Qwin_Module::instance($module);
        }
        foreach ($this->options['appPaths'] as $path) {
            $file = $path . $module->getPath() . $this->options['path'] . $this->_name . '.php';
            if (is_file($file)) {
                return $this->_appendFile($file);
            }
        }
    }
    
    /**
     * 加载微件语言
     * 
     * @param Qwin_Widget_Abstract|string $widget 微件对象或名称
     * @return false|Lang_Widget 语言文件不存在|加载成功
     */
    public function appendByWidget($widget)
    {
        if ($widget instanceof Qwin_Widget_Abstract) {
            $path = $widget->getPath();
        } else {
            $path = $this->_widget->getPath() . ucfirst($widget) . '/';
        }
        $file = $path . $this->options['path'] . $this->_name . '.php';
        return $this->appendByFile($file);
    }

    /**
     * 根据文件路径加载语言
     *
     * @param string $package
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
     * 翻译一个字符串
     *
     * @param string $name
     * @return string|null
     */
    public function t($name = null)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : $name;
    }
    
    /**
     * 翻译一个域名称
     * 
     * @param string $name 域名称
     * @return string
     */
    public function f($name = null)
    {
        $name = 'FLD_' . strtoupper($name);
        return isset($this->_data[$name]) ? $this->_data[$name] : $name;
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
    public function appendData(array $data)
    {
        $this->_data += $data;
        return $this;
    }
}
