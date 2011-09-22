<?php
/**
 * Class
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
 * @since       2011-09-15 20:56:18
 * @todo        get interface only implements by itself
 */

/**
 * @see Zend_Reflection_Class
 */
require_once 'Zend/Reflection/Class.php';

class Qwin_Reflection_Class extends Zend_Reflection_Class
{
    /**
     * @var array
     */
    protected $_events = null;
    
    /**
     * @var array
     */
    protected $_options = null;
    
    /**
     * Property that stored the options value
     * 
     * @var string 
     */
    protected $_optionName = '_defaults';
    
    /**
     * @var array
     */
    protected $_results = null;
    
    /**
     * 返回数组格式的内容
     * 
     * @return array 
     */
    public function toArray()
    {
        $tagsTree = array();
        try {
            $docblock = $this->getDocblock();
            $longDescription = $docblock->getLongDescription();
            $shortDescription = $docblock->getShortDescription();
            
            $tags = $docblock->getTags();
            /* @var $tag Qwin_Reflection_Docblock_Tag */
            foreach ($tags as $tag) {
                $tagsTree[$tag->getName()][] = $tag->toArray();
            }
            
            $version = $docblock->getTag('version');
            if (false == $version) {
                $version = '-';
            } else {
                $version = $version->getDescription();
            }
        } catch (Exception $e) {
            $docblock = '';
            $longDescription = null;
            $shortDescription = null;
            $version = PHP_VERSION;
        }

        $methods = array();
        /* @var $method Qwin_Reflection_Method */
        foreach ($this->getMethods() as $method) {
            $methods[$method->getName()] = $method->toArray();
        }
        ksort($methods);
        
        // TODO $propertiesValue
        $properties = array();
        $propertiesValue = $this->getDefaultProperties();
        /* @var $property Qwin_Reflection_Property */
        foreach ($this->getProperties() as $property) {
            $value = $propertiesValue[$property->getName()];
            $properties[$property->getName()] = $property->toArray() + array(
                'value' => $value,
                'valueText' => Qwin_Reflection::exportValue($value),
            );
        }
        
        // TODO as class Qwin_Reflection_Constant
        $constants = array();
        foreach ($this->getConstants() as $constant => $value) {
            $constants[$constant] = array(
                'name' => $constant,
                'varName' => $constant,
                'type' => gettype($value),
                'value' => $value,
                'modifiers' => 'const',
                'valueText' => Qwin_Reflection::exportValue($value),
            );
        }

        $class = $this->getName();
        $extends = class_parents($class);
        $interfaces = $this->getInterfaceNames();

        return array(
            'name' => $class,
            'version' => $version,
            'modifiers' => $this->getModifiers(),
            'longDescription' => $longDescription,
            'shortDescription' => $shortDescription,
            
            'parents' => $extends,
            'interfaces' => $interfaces,
            'inheritence' => $this->getInheritence(),
            
            'methods' => $methods,
            'constants' => $constants,
            'properties' => $properties,

            'events' => $this->getEvents(),
            'options' => $this->getOptions(),
            'results' => $this->getResults(),
            'tags' => $tagsTree,
        );
    }
    
    /**
     * Return class inheritence
     * 
     * @return array
     */
    public function getInheritence()
    {
        $class = $this->getName();
        $interfaces = $this->getInterfaceNames();
        $inheritence = array();
        
        while($parentClass = get_parent_class($class)) {
            $parentInstance = new Qwin_Reflection_Class($parentClass);
            $parentInterfaces = $parentInstance->getInterfaceNames();
            $inheritence[$class] = array_diff($interfaces, $parentInterfaces);
            sort($inheritence[$class]);
            $class = $parentClass;
            $interfaces = $parentInterfaces;
        }
        $inheritence[$class] = $interfaces;
        return $inheritence;
    }
    
    /**
     * Return events defined in docblock at tag "@event"
     * 
     * @return array|null
     */
    protected function _getEvents()
    {
        if (is_array($this->_events)) {
            return $this->_events;
        }
        
        try {
            $events = array();
            $docblock = $this->getDocblock();
            if ($docblock->hasTag('event')) {
                $eventTags = $docblock->getTags('event');
                /* @var $event Qwin_Reflection_Docblock_Tag_Event */
                foreach ($eventTags as $event) {
                    $events[$event->getMethodName()] = $event->toArray();
                }
            } else {
                $events = null;
            }
        } catch (Exception $e) {
            $events = null;
        }
        $this->_events = $events;
        return $events;
    }
    
    /**
     * Return events
     * 
     * @return array|null
     */
    public function getEvents()
    {
        return $this->_getEvents();
    }
    
    /**
     * Return options defined in option property
     * 
     * @return array|null 
     */
    protected function _getOptions()
    {
        if (is_array($this->_options)) {
            return $this->_options;
        }
        
        try {
            $options = array();
            $propertiesValue = $this->getDefaultProperties();
            $property = $this->getProperty($this->_optionName);
            $docblock = $property->getDocComment();
            
            $optionsText = $docblock->getTag('var')->getLongDescription();
            $optionsText = preg_split('/[\n\r]+/', $optionsText, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($optionsText as $option) {
                $option = preg_split('/[\s]+/', trim($option));
                if (3 > count($option)) {
                    $option = array_pad($option, 3, '');
                }
                if (array_key_exists($option[0], $propertiesValue[$this->_optionName])) {
                    $option[3] = Qwin_Reflection::exportValue($propertiesValue['_defaults'][$option[0]]);
                } else {
                    $option[3] = 'void';
                }
                $options[$option[0]] = array(
                    'name' => $option[0],
                    'type' => $option[1],
                    'description' => $option[2],
                    'value' => $option[3],
                );
            }
        } catch (Exception $e) {
            $options = null;
        }
        
        $this->_options = $options;
        return $options;
    }
    
    /**
     * Return options
     * 
     * @return array|null 
     */
    public function getOptions()
    {
        return $this->_getOptions();
    }
    
    /**
     * Return results defined in render method's return tag
     * 
     * @return array|null 
     */
    public function _getResults()
    {
        if (is_array($this->_results)) {
            return $this->_results;
        }
        
        try {
            $results = array();
            $result = $this->getMethod('render');
            $docblock = $result->getDocblock()->getTag('return')->getLongDescription();
            $results = preg_split('/[\n\r]+/', $docblock, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($results as $key => $value) {
                $value = preg_split('/[\s]+/', trim($value));
                if (3 > count($value)) {
                    $value = array_pad($value, 3, '');
                }
                '' == $value[2] && $value[2] = '-'; 
                $results[$key] = array(
                    'code' => $value[0],
                    'message' => $value[1],
                    'description' => $value[2],
                );
            }
        } catch (Exception $e) {
            $results = null;
        }
        
        $this->_results = $results;
        return $results;
    }
    
    /**
     * Return results
     * 
     * @return array|null
     */
    public function getResults()
    {
        return $this->_getResults();
    }
    
    /**
     * Return the reflection file of the declaring file.
     *
     * @return Qwin_Reflection_File
     */
    public function getDeclaringFile($reflectionClass = 'Qwin_Reflection_File')
    {
        return parent::getDeclaringFile($reflectionClass);
    }

    /**
     * Return the classes Docblock reflection object
     *
     * @param  string $reflectionClass Name of reflection class to use
     * @return Qwin_Reflection_Docblock
     * @throws Zend_Reflection_Exception for missing docblock or invalid reflection class
     */
    public function getDocblock($reflectionClass = 'Qwin_Reflection_Docblock')
    {
        return parent::getDocblock($reflectionClass);
    }

    /**
     * Get all reflection objects of implemented interfaces
     *
     * @param  string $reflectionClass Name of reflection class to use
     * @return array Array of Qwin_Reflection_Class
     */
    public function getInterfaces($reflectionClass = 'Qwin_Reflection_Class')
    {
        return parent::getInterfaces($reflectionClass);
    }

    /**
     * Return method reflection by name
     *
     * @param  string $name
     * @param  string $reflectionClass Reflection class to utilize
     * @return Qwin_Reflection_Method
     */
    public function getMethod($name, $reflectionClass = 'Qwin_Reflection_Method')
    {
        return parent::getMethod($name, $reflectionClass);
    }

    /**
     * Get reflection objects of all methods
     *
     * @param  string $filter
     * @param  string $reflectionClass Reflection class to use for methods
     * @return array Array of Qwin_Reflection_Method objects
     */
    public function getMethods($filter = -1, $reflectionClass = 'Qwin_Reflection_Method')
    {
        return parent::getMethods($filter, $reflectionClass);
    }

    /**
     * Get parent reflection class of reflected class
     *
     * @param  string $reflectionClass Name of Reflection class to use
     * @return Qwin_Reflection_Class
     */
    public function getParentClass($reflectionClass = 'Qwin_Reflection_Class')
    {
        return parent::getParentClass($reflectionClass);
    }

    /**
     * Return reflection property of this class by name
     *
     * @param  string $name
     * @param  string $reflectionClass Name of reflection class to use
     * @return Qwin_Reflection_Property
     */
    public function getProperty($name, $reflectionClass = 'Qwin_Reflection_Property')
    {
        return parent::getProperty($name, $reflectionClass);
    }

    /**
     * Return reflection properties of this class
     *
     * @param  int $filter
     * @param  string $reflectionClass Name of reflection class to use
     * @return array Array of Qwin_Reflection_Property
     */
    public function getProperties($filter = -1, $reflectionClass = 'Qwin_Reflection_Property')
    {
        return parent::getProperties($filter, $reflectionClass);
    }
}