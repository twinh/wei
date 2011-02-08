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
     * 已信任的微件列表
     * @var array
     */
    protected $_trusted = array();

    public function __construct($path)
    {
        if (is_dir($path)) {
            $this->setRootPath($path);
        }
    }
    
    public function setRootPath($path)
    {
        $this->_rootPath = $path;
        return $this;
    }

    /**
     * 获取一个微件的实例化对象
     *
     * @param string $name 微件类名称,不带Widget_前缀
     * @return Qwin_Widget_Abstarct
     */
    public function get($name)
    {
        if (false == $this->isExists($name)) {
            throw new Qwin_Widget_Exception('Can not find the widget : "' . $name . '"');
        }

        $class = ucfirst($name) . '_Widget';

        return Qwin::call($class);
    }

    /**
     * 检查微件是否存在
     *
     * @param string $name
     * @return boolen
     */
    public function isExists($name)
    {
        // 已经检测过
        if (isset($this->_trusted[$name])) {
            return true;
        }

        // 查看主文件是否存在
        $file = $this->_rootPath . '/' . $name . '/widget.php';
        if (!is_file($file)) {
            return false;
        }

        // 加载并查看类是否存在
        require_once $file;
        $class = ucfirst($name) . '_Widget';
        if (!class_exists($class)) {
            return false;
        }

        // 加入信任列表中
        $this->_trusted[$name] = true;
        return true;
    }
}
