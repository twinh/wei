<?php
/**
 * Controller
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
 * @since       2011-08-22 23:44:15
 */

class Doc_Controller extends Controller_Widget
{
    public function actionIndex()
    {
        $request = $this->_request;
        $class = $request->get('class');

        if (empty($class)) {
            $this->_view->alert('Class should not be empty!');
        }
        
        if (!class_exists($class) && !interface_exists($class)) {
            $this->_view->alert('Class "' . $class . '" not found.');
        }

        // TODO cleanup use Qwin_Reflection::toArray() to get all codes
        // TODO 只能通过硬编码确认类是否为微件类?
        if ('Qwin_Widget_Abstract' == get_parent_class($class)) {
            $type = 'WIDGET';
        } else {
            $type = 'CLASS';
        }

        $object = new Zend_Reflection_Class($class);
        $events = array();
        try {
            $docblock = $object->getDocblock('Qwin_Reflection_Docblock');
            // 获取名称
            $name = $docblock->getTag('name');
            if (false == $name) {
                $name = $class;
            } else {
                $name = $name->getDescription();
            }
            
            // 获取版本号
            $version = $docblock->getTag('version');
            if (false == $version) {
                $version = '-';
            } else {
                $version = $version->getDescription();
            }
            $description = $docblock->getShortDescription();
            
            $eventTags = $docblock->getTags('event');
            /* @var $event Qwin_Reflection_Docblock_Tag_Event */
            foreach ($eventTags as $event) {
                $paramTemp = array();
                foreach ($event->getParams() as $param) {
                    if (isset($param['value'])) {
                        $paramTemp[] = $param['type'] . $param['name'] . ' = ' . $param['value'];
                    } else {
                        $paramTemp[] = $param['type'] . $param['name'];
                    }
                }
                
                $events[$event->getMethodName()] = array(
                    'name' => $event->getMethodName(),
                    'param' => '(' . implode(', ', $paramTemp) . ')',
                    'description' => $event->getDescription(),
                    'return' => $event->getType(),
                );
            }
        } catch (Exception $e) {
            $docblock = '';
            $name = $class;
            $version = PHP_VERSION;
            $description = '';
        }
        $data['events'] = $events;

        $propertiesValue = $object->getDefaultProperties();
        // 获取选项和回调事件
        if ('WIDGET' == $type) {
            $optionsProperty = $object->getProperty('_defaults');
            $comment = $optionsProperty->getDocComment('Qwin_Reflection_Docblock');
            $options = $comment->getTag('var')->getLongDescription();
            $options = preg_split('/[\n\r]+/', $options, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($options as $key => $value) {
                $value = preg_split('/[\s]+/', trim($value));
                if (3 > count($value)) {
                    $value = array_pad($value, 3, '');
                }
                if (array_key_exists($value[0], $propertiesValue['_defaults'])) {
                    $defaultValue = $propertiesValue['_defaults'][$value[0]];
                    if (is_array($defaultValue)) {
                        if (empty($defaultValue)) {
                            $defaultValue = 'array( )';
                        } else {
                            $defaultValue = 'array(..)';
                        }
                    } elseif (is_null($defaultValue)) {
                        $defaultValue = 'null';
                    } else {
                        $defaultValue = var_export($defaultValue, true);
                    }
                    $value[3] = $defaultValue;
                } else {
                    $value[3] = 'void';
                }
                $options[$key] = $value;
            }
            $data['options'] = $options;
        
            // 获取操作结果
            $result = $object->getMethod('render');
            $resultDocblock = $result->getDocblock('Qwin_Reflection_Docblock')->getTag('return')->getLongDescription();
            $results = preg_split('/[\n\r]+/', $resultDocblock, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($results as $key => $value) {
                $value = preg_split('/[\s]+/', trim($value));
                if (3 > count($value)) {
                    $value = array_pad($value, 3, '');
                }
                '' == $value[2] && $value[2] = '-'; 
                $results[$key] = $value;
            }
            $data['results'] = $results;
        }
        
        $constants = $object->getConstants();
        foreach ($constants as $constant => $value) {
            $data['properties'][$constant] = array(
                'name' => $constant,
                'type' => gettype($value),
                'value' => var_export($value, true),
                'modifiers' => 'const',             
            );
        }
        
        // 获取属性
        $properties = $object->getProperties();
        //$propertiesValue = $object->getDefaultProperties();
        foreach ($properties as $property) {
            // 获取名称
            $propertyName = '$' . $property->getName();

            // 获取类型
            /* @var $propertyDocblock Qwin_Reflection_Docblock */
            $propertyDocblock = $property->getDocComment('Qwin_Reflection_Docblock');
            if (false != $propertyDocblock) {
                /* @var $var Qwin_Reflection_Docblock_Tag_Var */
                $var = $propertyDocblock->getTag('var');
                if (false != $var) {
                    $propertyType = $var->getType();
                } else {
                    $propertyType = 'void';
                }
            } else {
                $propertyType = 'void';
            }
            
            $propertyValue = $propertiesValue[$property->getName()];
            $propertiesFullValue = var_export($propertiesValue, true);
            if (is_array($propertyValue)) {
                if (empty($propertyValue)) {
                    $propertyValue = 'array( )';
                } else {
                    $propertyValue = 'array(..)';
                }
            } elseif (is_null($propertyValue)) {
                $propertyValue = 'null';
            } else {
                $propertyValue = var_export($propertyValue, true);
            }

            $data['properties'][$propertyName] = array(
                'modifiers' => implode(' ', Reflection::getModifierNames($property->getModifiers())),
                'name' => $propertyName,
                'type' => $propertyType,
                'value' => $propertyValue,
            );
        }

        // 获取方法
        $methods = $object->getMethods();
        /* @var $method Zend_Reflection_Method */
        foreach ($methods as $method) {
            // 处理参数
            $params = $method->getParameters();
            $paramTemp = array();
            foreach ($params as $param) {
                try {
                    $paramType = $param->getType();
                } catch (Zend_Reflection_Exception $e) {
                    $paramType = '';
                }
                // 
                if ('' == $paramType) {
                    // Parameter #0 [ <optional> $options = NULL ]
                    $paramString = $param->__toString();
                    $pos = strpos($paramString, '>');
                    $pos2 = strpos($paramString, ' ', $pos + 2);
                    $paramType = substr($paramString, $pos + 2, $pos2 - $pos - 2);
                    if ('$' == $paramType[0]) {
                        $paramType = '';
                    }
                }
                if ($param->isDefaultValueAvailable()) {
                    
                    $defaultValue = var_export($param->getDefaultValue(), true);
                    if ('NULL' == $defaultValue) {
                        $defaultValue = 'null';
                    }
                    $paramTemp[] = $paramType . ' $' . $param->getName() . ' = ' . $defaultValue;
                } else {
                    $paramTemp[] = $paramType . ' $' . $param->getName();
                }
            }
            $paramTemp = implode(', ', $paramTemp);
            if (empty($paramTemp)) {
                $paramTemp = 'void';
            }
            $paramTemp = '( ' . $paramTemp . ' )';

            // TODO 如何获得内部方法返回值,从外部文档?
            try {
                $methodDocblock = $method->getDocblock();
            } catch (Zend_Reflection_Exception $e) {
                $methodDocblock = false;
            }
            if (false != $methodDocblock) {
                /* @var $return Zend_Reflection_Docblock_Tag_Return */
                $return = $methodDocblock->getTag('return');
                if (false != $return) {
                    $return = $return->getType();
                } else {
                    $return = '<em>(unknown)</em>';
                }
                $methodDescription = $methodDocblock->getShortDescription();
            } else {
                $return = '<em>(unknown)</em>';
                $methodDescription = '';
            }

            $data['methods'][$method->getName()] = array(
                'modifiers' => implode(' ', Reflection::getModifierNames($method->getModifiers())),
                'name' => $method->getName(),
                'param' => $paramTemp,
                'return' => $return,
                'description' => $methodDescription,
            );
        }

        $data['name'] = $object->getName();
        $data['overview'] = array(
            'name' => $name,
            'version' => $version,
            'description' => $description,
        );

        $this->_view->assign(get_defined_vars());
    }
}