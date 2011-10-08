<?php
/**
 * Parameter
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
 * @since       2011-09-16 12:52:51
 * @todo        method get parameter text
 */

class Qwin_Reflection_Parameter extends Zend_Reflection_Parameter
{
    /**
     *
     * @return array
     * @todo get default value
     */
    public function toArray()
    {
        try {
            $type = $this->getType();
        } catch (Zend_Reflection_Exception $e) {
            $type = null;
        }
        
        // try to catch type from exported string
        if (!isset($type)) {
            // eg: Parameter #0 [ <optional> $options = NULL ]
            $string = $this->__toString();
            $pos = strpos($string, '>');
            $pos2 = strpos($string, ' ', $pos + 2);
            $type = substr($string, $pos + 2, $pos2 - $pos - 2);
            if ('$' == $type[0] || '&' == $type[0]) {
                $type = null;
            }
        }
        
        $name = $this->getName();
        return array(
            'name' => $name,
            'varName' => ($this->isPassedByReference() ? '&' : '') . '$' . $name,
            'type' => $type,
            'value' => null,
            'valueText' => null,
        );
    }
    
    /**
     * Get declaring class reflection object
     *
     * @param  string $reflectionClass Reflection class to use
     * @return Zend_Reflection_Class
     */
    public function getDeclaringClass($reflectionClass = 'Zend_Reflection_Class')
    {
        return parent::getDeclaringClass($reflectionClass);
    }

    /**
     * Get class reflection object
     *
     * @param  string $reflectionClass Reflection class to use
     * @return Zend_Reflection_Class
     */
    public function getClass($reflectionClass = 'Zend_Reflection_Class')
    {
        return parent::getClass($reflectionClass);
    }

    /**
     * Get declaring function reflection object
     *
     * @param  string $reflectionClass Reflection class to use
     * @return Zend_Reflection_Function|Zend_Reflection_Method
     */
    public function getDeclaringFunction($reflectionClass = null)
    {
        $phpReflection = ReflectionParameter::getDeclaringFunction();
        if ($phpReflection instanceof ReflectionMethod) {
            $baseClass = 'Qwin_Reflection_Method';
            if (null === $reflectionClass) {
                $reflectionClass = $baseClass;
            }
            $zendReflection = new $reflectionClass($this->getDeclaringClass()->getName(), $phpReflection->getName());
        } else {
            $baseClass = 'Qwin_Reflection_Function';
            if (null === $reflectionClass) {
                $reflectionClass = $baseClass;
            }
            $zendReflection = new $reflectionClass($phpReflection->getName());
        }
        if (!$zendReflection instanceof $baseClass) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Invalid reflection class provided; must extend ' . $baseClass);
        }
        unset($phpReflection);
        return $zendReflection;
    }
}