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
 */

class Qwin_Widget
{
    /**
     * 已加载的微件
     * @var array
     */
    protected $_loaded = array();

    /**
     * 微件所在目录
     * @var string
     */
    protected $_rootPath;

    /**
     * 获取当前类的实例化对象
     *
     * @return Qwin_Widget
     */
    public function __construct($path = null)
    {
        $path && $this->setRootPath($path);
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
    
    public function getPath()
    {
        return $this->_rootPath;
    }

    /**
     * 根据类名获取微件
     *
     * @param string $class 类名
     * @return mixed
     */
    public function getByClass($class)
    {
        if (!class_exists($class)) {
            require_once 'Qwin/Widget/Exception.php';
            throw new Qwin_Widget_Exception('Class "' . $class . '" not found.');
        }

        // 微件类应该继承自抽象类或是实现其接口类
        if (!is_subclass_of($class, 'Qwin_Widget_Abstract')) {
            $reflection = new ReflectionClass($class);
            if (!in_array('Qwin_Widget_Interface', $reflection->getInterfaceNames())) {
                require_once 'Qwin/Widget/Exception.php';
                throw new Qwin_Widget_Exception('Class "' . $class . '" is not a widget class.');
            }
        }
        
        return $this->_loaded[$class] = Qwin::call($class);
    }
    
    /**
     * 获取一个微件的实例化对象
     * 
     * @param string $name 微件类名称,不带"_Widget"后缀
     * @return Qwin_Widget_Abstarct 微件对象
     */
    public function call($name)
    {
        $class = $name . '_Widget';
        if (isset($this->_loaded[$class])) {
            return $this->_loaded[$class];
        }

        // 查看主文件是否存在
        $file = $this->_rootPath . $name . '/Widget.php';
        if (!is_file($file)) {
            throw new Qwin_Widget_Exception('Widget "' . $name . '" not found.');
        }
        
        // 加载类文件
        require_once $file;
        return $this->getByClass($class);
    }

    /**
     * 获取一个微件的实例化对象
     *
     * @param string $name 微件类名称,不带"_Widget"后缀
     * @return Qwin_Widget_Abstarct 微件对象
     */
    public function get($name)
    {
        return $this->call($name);
    }
    
    public function register(Qwin_Widget_Abstract $object)
    {
        $this->_loaded[get_class($object)] = $object;
        return $this;
    }

    /**
     * 检查是否调用过该微件类
     *
     * @param string|Qwin_Widget_Abstract $class 微件对象或类名
     * @return bool
     */
    public function isCalled($class)
    {
        is_object($class) && $class = get_class($class);
        return isset($this->_loaded[$class]);
    }
}
