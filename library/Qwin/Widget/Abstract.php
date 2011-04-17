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

abstract class Qwin_Widget_Abstract implements Qwin_Widget_Interface
{
    /**
     * 默认配置
     * @var array
     */
    protected $_defaults = array(
        'lang' => false,
    );

    /**
     * 选项
     *
     * @var array
     */
    protected $_options = array();

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

    /**
     * 语言对象
     * @var Qwin_Application_Language
     */
    protected $_lang;
    
    /**
     * 初始化
     *
     * @param array $options 选项
     * @todo 不是每一个微件都需要此流程
     */
    public function __construct(array $options = array())
    {
        // 检查是否通过微件管理对象获得,不是则
        $this->_widget = Qwin::call('Qwin_Widget');
        if (!$this->_widget->isCalled($this)) {
            $params = $this->_widget->getParams($this);
            isset($params[0]) && $options += $params[0];
        }

        $options = $this->_options = $options + $this->_defaults;

        // 初始化根目录
        if (isset($options['rootPath']) && is_dir($options['rootPath'])) {
            $this->setRootPath($options['rootPath']);
        }
        
        // 加载语言
        $this->_lang = Qwin::call('-lang');
        if (isset($options['lang']) && $options['lang']) {
            if ($this->_lang instanceof Qwin_Application_Language) {
                $this->_lang->appendByFile($this->_rootPath . 'language/' . $this->_lang->getName() . '.php');
            }
        }
    }

    public function render($options)
    {
        return $this;
    }

    /**
     * 获取默认选项
     *
     * @param string|null $name 名称
     * @return mixed
     */
    public function getDefault($name = null)
    {
        if (null == $name) {
            return $this->_defaults;
        }
        if (isset($this->_defaults[$name])) {
            return $this->_defaults[$name];
        }
        throw new Qwin_Widget_Exception('Undefine default option "' . $name . '".');
    }

    /**
     * 设置默认选项
     *
     * @param string $name 选项名称
     * @param mixed $value 选项的值
     * @return Qwin_Widget_Abstract 当前对象
     */
    public function setDefault($name, $value = null)
    {
        if (isset($this->_defaults[$name])) {
            $this->_defaults[$name] = $value;
            return $this;
        }
        throw new Qwin_Widget_Exception('Undefine default option "' . $name . '".');        
    }

    /**
     * 获取选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_options;
        }
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        throw new Qwin_Widget_Exception('Undefine option "' . $name . '".');
    }

    /**
     * 设置选项
     *
     * @param string $name 配置名称
     * @param mixed $value 配置的值
     * @return Qwin_Widget_Abstract 当前对象
     */
    public function setOption($name, $value = null)
    {
        isset($this->_options[$name]) && $this->_options[$name] = $value;
        return $this;
    }

    /**
     * 生成属性字符串
     *
     * @param array $options 属性数组,键名表示属性名称,值表示属性值
     * @return string 属性字符串
     */
    public function renderAttr($options)
    {
        $attr = '';
        foreach ($options as $name => $value) {
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
    public function loadLanguage($widget = null)
    {
        /* @var $lang Qwin_Application_Language */
        $lang = Qwin::call('-lang');
        if (!$widget) {
            $filePath = $this->_rootPath;
        } else {
            $filePath = dirname($this->_rootPath) . '/' . $widget . '/';
        }
        $lang->appendByFile($filePath . 'language/' . $lang->getName() . '.php');
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