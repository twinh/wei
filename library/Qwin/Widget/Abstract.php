<?php
/**
 * Abstract
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-29 18:13:42
 */

abstract class Qwin_Widget_Abstract
{
    /**
     * 配置选项
     *
     * @var array
     */
    protected $_option = array();

    /**
     * 自动加载培训
     *
     * @var array
     */
    protected $_autoload = array(
        'lang' => false,
    );

    /**
     * 微件的根目录
     * @var string
     */
    protected $_rootPath;
    
    /**
     * 微件的缓存目录
     * @var string
     */
    protected $_cachePath;

    /*
     * 微件单例对象
     * @var Qwin_Widget
     */
    protected $_widget;
    
    abstract public function render($option);

    /**
     * 初始化
     */
    public function  __construct()
    {
        $this->_widget = Qwin::call('Qwin_Widget');
    }

    /**
     * 安装微件
     *
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 卸载微件
     *
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 获取配置选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_option;
        }
        return isset($this->_option[$name]) ? $this->_option[$name] : null;
    }

    /**
     * 生成属性字符串
     *
     * @param array $option 属性数组,键名表示属性名称,值表示属性值
     * @return string 属性字符串
     */
    public function renderAttr($option)
    {
        $attr = '';
        foreach ($option as $name => $value) {
            $attr .= $name . '="' . htmlspecialchars($value) . '" ';
        }
        return $attr;
    }

    /**
     * 设置微件根路径
     *
     * @param string $path 路径
     * @return Qwin_Widget_Abstract 当前对象
     */
    public function setRootPath($path)
    {
        if (is_dir($path)) {
            $this->_rootPath = $path;
            return $this;
        }
        throw new Qwin_Widget_Exception('Path "' . $path . '" no found.');
    }

    /**
     * 获取当前根目录
     *
     * @return string
     */
    public function getRootPath()
    {
        return $this->_rootPath;
    }

    /**
     * 加载语言
     *
     * @return Qwin_Widget_Abstract 当前对象
     * @todo 更合适的方式加载
     */
    public function loadLanguage()
    {
        /* @var $lang Qwin_Application_Language */
        $lang = Qwin::call('-lang');
        $lang->appendByFile($this->_rootPath . 'language/' . $lang->getName() . '.php');
        return $this;
    }

    /**
     * 设置缓存路径
     *
     * @param string $path 路径
     * @return Qwin_Widget_Abstract 当前对象
     */
    public function setCachePath($path)
    {
        if (is_dir($path)) {
            $this->_cachePath = $path;
            return $this;
        }
        throw new Qwin_Widget_Exception('Path "' . $path . '" no found.');
    }

    /**
     * 获取缓存路径
     * 
     * @return string
     */
    public function getCachePath()
    {
        return $this->_cachePath;
    }

    /**
     * 自动加载部分常用类
     *
     * @param array $option 配置选项
     * @return Qwin_Widget_Abstract 当前对象
     * @todo 耦合.
     */
    public function autoload(array $option = null)
    {
        $this->_autoload = array_merge($this->_autoload, (array)$option);
        if ($this->_autoload['lang']) {
            /* @var $lang Qwin_Application_Language */
            $lang = Qwin::call('-lang');
            $lang->appendByFile($this->_rootPath . 'language/' . $lang->getName() . '.php');
        }
        return $this;
    }

    /**
     * 合并两个数组
     *
     * @param array $array1 数组1
     * @param array $array2 数组2
     * @return array
     */
    protected function merge(array $array1 = null, array $array2 = null)
    {
        if (null == $array2) {
            return $array1;
        }
        foreach ($array2 as $key => $val) {
            if (is_array($val)) {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->merge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }
}