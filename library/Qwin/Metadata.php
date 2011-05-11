<?php
/**
 * Metadata
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-26 13:14:28
 *
 */

class Qwin_Metadata
{
    /**
     * 当前对象
     *
     * @var Qwin_Metadata
     */
    protected static $_instance;

    /**
     * 驱动映射数组
     *
     * @var array
     */
    protected $_drivers = array(
        'fields'    => 'Qwin_Metadata_Element_Fields',
        'list'      => 'Qwin_Metadata_Element_List',
        'form'      => 'Qwin_Metadata_Element_Form',
        'group'     => 'Qwin_Metadata_Element_Group',
        'model'     => 'Qwin_Metadata_Element_Model',
        'db'        => 'Qwin_Metadata_Element_Db',
        'page'      => 'Qwin_Metadata_Element_Page',
    );

    /**
     * 实例化的元数据对象数组
     *
     * @var array
     */
    protected $_objects = array();

    /**
     * 不允许在外部初始化
     */
    final private function  __construct()
    {
    }

    /**
     * 获取当前类的实例化对象
     *
     * @return Qwin_Metadata
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 获取一个元数据对象
     *
     * @param string $class 类名
     * @return object 元数据对象
     * @todo 当元数据不存在,是否应该加载父元数据?
     */
    public function get($class)
    {
        $lower = strtolower($class);
        
        if (isset($this->_objects[$lower])) {
            return $this->_objects[$lower];
        }

        if (!class_exists($class)) {
            throw new Qwin_Application_Metadata_Exception('Class "' . $class . '" not found.');
        }

        // 初始化并设置元数据
        if(is_subclass_of($class, 'Qwin_Metadata_Abstract')) {
            $object = new $class;
            $object->setManager($this)
                   ->setMetadata();
            $this->_objects[$class] = $object;
            return $object;
        } else {
            require_once 'Qwin/Metadata/Exception.php';
            throw new Qwin_Metadata_Exception('Class "' . $class . '" is not the sub class of "Qwin_Metadata_Abstract".');
        }
    }

    /**
     * 设置一个元数据对象
     *
     * @param string $class 类名,或任意字符串
     * @param Qwin_Metadata_Abstract $object 元数据对象
     */
    public function set($class, Qwin_Metadata_Abstract $object)
    {
        $class = (string)$class;
        $this->_objects[$class] = $object;
        return $this;
    }

    /**
     * 设置驱动类
     *
     * @param string $name 驱动名称
     * @param string $class 类名
     * @return Qwin_Metadata 当前对象
     */
    public function setDriver($name, $class)
    {
        if (is_subclass_of($class, 'Qwin_Metadata_Element_Driver')) {
            $this->_drivers[strtolower($name)] = $class;
        } else {
            require_once 'Qwin/Metadata/Exception.php';
            throw new Qwin_Metadata_Exception('Class "' . $class . '" is not the sub class of "Qwin_Metadata_Element_Driver".');
        }
        return $this;
    }

    /**
     * 获取驱动类名
     *
     * @param string|null $name 类名,留空表示获取驱动数组
     * @return string|array 类名或类名数组
     */
    public function getDriver($name = null)
    {
        if (null === $name) {
            return $this->_drivers;
        }
        return isset($this->_drivers[$name]) ? $this->_drivers[$name] : null;
    }
    
    /**
     * 验证是否为合法元数据变量
     * 
     * @param Qwin_Metadata_Abstract $meta 
     * @return bool
     */
    public static function isValid($meta)
    {
        if (is_object($meta) && $meta instanceof Qwin_Metadata_Abstract) {
            return true;
        }
        return false;
    }
}
