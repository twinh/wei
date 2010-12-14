<?php
/**
 * Abstract
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
 * @subpackage  Struct
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       v0.5.1 2010-12-10 09:48:40
 */

abstract class Qwin_Struct_Abstract implements Qwin_Struct_Interface
{
    protected $_sample = array(
        'key' => 'value',
        'key-2' => 'value-2'
    );

    protected $_data;

    public function  __construct()
    {
        
    }

    public function addElement($element, array $option = null)
    {
        if (is_array($element)) {
            $key = key($element);
            $this->_data[$key] = $element[$key];
        } else {
            $this->_data[] = $element;
        }
    }

    public function addElementList(array $elementList, array $option = null)
    {
        foreach ($elementList as $element) {
            $this->addElement($element, $option);
        }
        return $this;
    }

    public function removeElement($name)
    {
        if (isset($this->_data[$name])) {
            unset($name);
        }
        return $this;
    }

    public function isElementExists($name)
    {
        return isset($this->_data[$name]);
    }

    public function clear()
    {
        $this->_data = array();
        return $this;
    }

    public function fromArray(array $array)
    {
        $this->_data = $array;
    }

    /**
     * 
     *
     * @param array $array
     */
    public function fromTrustedArray(array $array)
    {
        $this->_data = $array;
    }

    public function toArray()
    {
        return $this->_data;
    }

    public function valid($data)
    {
        return false;
    }
}
