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

    /**
     * 根据类名获取微件
     *
     * @param string $class 类名
     * @return mixed
     */
    public function getByClass($class)
    {
        return $this->_loaded[$class] = $this->_callClass($class);
    }

    /**
     * 获取一个微件的实例化对象
     *
     * @param string $name 微件类名称,不带Widget_前缀
     * @return Qwin_Widget_Abstarct
     */
    public function get($name)
    {
        $sign = strtolower($name);
        if (isset($this->_loaded[$sign])) {
            return $this->_loaded[$sign];
        }

        // 查看主文件是否存在
        $file = $this->_rootPath . $sign . '/Widget.php';
        if (!is_file($file)) {
            return false;
        }

        // 加载类文件
        require_once $file;
        $class = ucfirst($name) . '_Widget';

        // 合并自定义的参数和配置中的参数
        $param = array(
            array(
                'rootPath'  => $this->_rootPath . $sign . '/',
                'lang'      => true,
            ),
        );
        $configParam = (array)Qwin::config($class);
        if (!empty($configParam)) {
            $param[0] += current($configParam);
        }

        // 初始化
        $widget = $this->_callClass($class, $param);
        $this->_loaded[$sign] = $widget;

        return $widget;
    }

    /**
     * 初始化一个微件类
     *
     * @param string $class 类名
     */
    public function _callClass($class, array $param = null)
    {
        if (!class_exists($class)) {
            require_once 'Qwin/Widget/Exception.php';
            throw new Qwin_Widget_Exception('Class "' . $class . '" not found.');
        }

        // 微件类应该继承自抽象类
        if (!is_subclass_of($class, 'Qwin_Widget_Abstract')) {
            require_once 'Qwin/Widget/Exception.php';
            throw new Qwin_Widget_Exception('Class "' . $class . '" is not the subclass of "Qwin_Widget_Abstract".');
        }

        // 初始化类
        /* @var $widget Qwin_Widget_Abstract */
        return Qwin::call($class, $param);
    }
}
