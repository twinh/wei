<?php
/**
 * Extension
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
 * @subpackage  Class
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-14 10:57:28
 */

class Qwin_Class_Extension
{
    protected $_namespace;
    protected $_currentNamespace;

    /**
     * 创建/设置一个命名空间,当命名空间存在时,会重新初始化
     *
     * @param string $name 命名空间的名称
     * @return object 当前类
     */
    public function setNamespace($name)
    {
        $this->_namespace[$name] = array();
        $this->_currentNamespace = $name;
        return $this;
    }

    /**
     * 设置当前命名空间
     *
     * @param string $name 存在的命名空间名称
     * @return object 当前对象
     */
    public function setCurrentNamespace($name)
    {
        if(isset($this->_namespace[$name]))
        {
            $this->_currentNamespace = $name;
            return $this;
        }
        require_once 'Qwin/Class/Extension/Exception.php';
        throw new Qwin_Class_Extension_Exception('The namespace ' . $name . ' is not setted.');
    }

    /**
     * 为当前的命名空间增加一个类
     *
     * @param string $class 存在的类名
     * @return obejct 当前对象
     */
    public function addClass($class)
    {
        if(class_exists($class))
        {
            $methodList = get_class_methods($class);
            foreach($methodList as $method)
            {
                $this->_namespace[$this->_currentNamespace][$method] = $class;
            }
        }
        return $this;
    }

    /**
     * 在不考虑类和方法是否存在及对应的情况下设置类和方法的对应关系
     *
     * @param string $class 类名
     * @param string $method 方法名
     * @return object 当前对象
     */
    public function setMethod($class, $method)
    {
        $this->_namespace[$this->_currentNamespace][$method] = $class;
        return $this;
    }

    /**
     * 获取方法所在的类
     *
     * @param string $method 方法的名称
     * @return object 当前对象
     */
    public function getClass($method)
    {
        if(isset($this->_namespace[$this->_currentNamespace][$method]))
        {
            return $this->_namespace[$this->_currentNamespace][$method];
        }
        return false;
    }
}
