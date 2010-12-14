<?php
/**
 * List
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
 * @since       v0.5.1 2010-12-10 09:46:49
 * @todo        当元素值为对象时,强制类型转换会出错
 */

class Qwin_Struct_List extends Qwin_Struct_Abstract
{
    /**
     * 初始键名
     *
     * @var int
     */
    protected $_initialkey = 1;

    protected $_data;

    public function addElement($element, array $option = null)
    {
        // 如果是数组,将数组键名作为元素名,数组值作为元素值
        if (is_array($element)) {
            $key = key($element);
            $this->_data[$key] = (string)$element[$key];
        } else {
            if (isset($this->_data[$this->_initialkey])) {
                $this->_data[] = (string)$element;
            } else {
                $this->_data[$this->_initialkey] = (string)$element;
            }
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
        foreach ($array as $key => $element) {
            $this->_data[$key] = (string)$element;
        }
        return $this;
    }

    public function fromDbArray(array $array, $keyName = 'id', $valueName = 'name')
    {
        $key = key($array);
        if (!isset($array[$key][$keyName])) {
            throw new Qwin_Struct_Exception('The key name "' . $id . '" is not exists.');
        }

        if (!isset($array[$key][$valueName])) {
            throw new Qwin_Struct_Exception('The value name "' . $valueName . '" is not exists.');
        }

        foreach ($array as $row) {
            $this->_data[$row[$keyName]] = $row[$valueName];
        }
        return $this;
    }
}
