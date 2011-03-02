<?php
/**
 * Widget
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
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-20 15:26:25
 * @todo        形成一个规范的体系
 */

class Qwin_Widget
{
    /**
     * 微件目录
     * @var string
     */
    protected $_rootPath;

    /**
     * 已加载的微件
     * @var array
     */
    protected $_loaded = array();

    /**
     * 初始化
     *
     * @param string $path
     * @return Qwin_Widget 当前对象
     */
    public function __construct($path = null)
    {
        if ($path) {
            $this->setRootPath($path);
        }
    }

    /**
     * 设置根路径
     *
     * @param string $path 路径
     * @return Qwin_Widget 当前对象
     */
    public function setRootPath($path)
    {
        if (is_dir($path)) {
            $this->_rootPath = $path;
            return $this;
        }
        
        throw new Qwin_Widget_Exception('Path "' . $path . '" not found.');
    }

    /**
     * 获取一个微件的实例化对象
     *
     * @param string $name 微件类名称,不带Widget_前缀
     * @return Qwin_Widget_Abstarct
     */
    public function get($name)
    {
        $lower = strtolower($name);
        if (isset($this->_loaded[$lower])) {
            return $this->_loaded[$lower];
        }

        // 查看主文件是否存在
        $file = $this->_rootPath . $lower . '/widget.php';
        if (!is_file($file)) {
            return false;
        }

        // 加载并查看类是否存在
        require_once $file;
        $class = $name . '_Widget';
        if (!class_exists($class)) {
            return false;
        }

        // 微件类应该继承自抽象类
        if (!is_subclass_of($class, 'Qwin_Widget_Abstract')) {
            require_once 'Qwin/Widget/Exception.php';
            throw new Qwin_Widget_Exception('Class "' . $class . '" is not the subclass of "Qwin_Widget_Abstract"');
        }

        // 初始化类
        /* @var $widget Qwin_Widget_Abstract */
        $widget = Qwin::call($class);
        $this->_loaded[$lower] = $widget;

        // 设置根目录,自动加载
        $widget->setRootPath($this->_rootPath . $lower . '/')
               ->autoload();    

        return $widget;
    }
}
