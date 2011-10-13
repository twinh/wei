<?php
/**
 * Qwin Framework
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Constant
 * 
 * @package     Qwin
 * @subpackage  Reflection
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-09-25 21:03:23
 */
class Qwin_Reflection_Constant implements Reflector
{
    /**
     * Constant name
     * @var string 
     */
    protected $_name;
    
    /**
     * Constant value
     * @var mixed
     */
    protected $_value;
    
    /**
     * Constant class name
     * @var string 
     */
    protected $_class;

    /**
     * Instance constant object
     * 
     * @param string $name constant name
     * @param mixed $class class name or instanced object
     * @todo how about rewrite ReflectionClass::getConstant
     */
    public function __construct($name, $class = null)
    {
        if ($class) {
            $object = new Qwin_Reflection_Class($class);
            // use getConstants intance of getConstant, because constant counld be defined as false,
            // and when a contant not found, getConstant also return false
            $constants = $object->getConstants();
            if (!array_key_exists($name, $constants)) {
                require_once 'Qwin/Reflection/Exception.php';
                throw new Qwin_Reflection_Exception('Undefined constant "' . $name . '" in class "' . $object->getName() . '".');
            }
            $this->_class = $object->getName();
        } else {
            if (!defined($name)) {
                require_once 'Qwin/Reflection/Exception.php';
                throw new Qwin_Reflection_Exception('Undefined constant "' . $name . '".');
            }
            $constants = get_defined_constants();
        }
        $this->_value = $constants[$name];
        $this->_name = $name;
    }
    
    public function toArray()
    {
        return array(
            'name' => $this->_name,
            'varName' => $this->_name,
            'type' => gettype($this->_value),
            'value' => $this->_value,
            'modifiers' => 'const',
            'valueText' => Qwin_Reflection::exportValue($this->_value),
        );
    }
    
    /**
     * Export constant
     * 
     * @return string
     * @todo format? 
     */
    public static function export()
    {
        return null;
    }
    
    /**
     * To string
     * 
     * @return string 
     * @todo format?
     */
    public function __toString()
    {
        return '';
    }
    
    /**
     * Get constant value
     * 
     * @return mixed 
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Get constant name
     * 
     * @return string 
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Get constant class name
     * 
     * @return string 
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * Get reflection of declaring class
     *
     * @param string $reflectionClass Name of reflection class to use
     * @return Zend_Reflection_Class
     */
    public function getDeclaringClass($reflectionClass = 'Qwin_Reflection_Class')
    {
        $class = $this->getClass();
        if (!$class) {
            require_once 'Qwin/Reflection/Exception.php';
            throw new Qwin_Reflection_Exception('Constant "' . $this->getName() . '" is not defined by class.');
        }
        $reflection = new $reflectionClass($class);
        if (!$reflection instanceof Qwin_Reflection_Class) {
            require_once 'Qwin/Reflection/Exception.php';
            throw new Qwin_Reflection_Exception('Invalid reflection class "' . $reflectionClass . '" provided; must extend Qwin_Reflection_Class');
        }
        return $reflection;
    }
}