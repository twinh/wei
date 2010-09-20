<?php
/**
 * Access
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
 * @since       2010-7-27 15:41:46
 */

abstract class Qwin_Metadata_Abstract implements ArrayAccess, Iterator
{
    protected $_data;

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->_data[$name]))
        {
            return $this->_data[$name];
        }
        return null;
    }

    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        if(isset($this->_data[$offset]))
        {
            return $this->_data[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    public function next()
    {
        return next($this->_data);
    }

    public function key()
    {
        return key($this->_data);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->_data);
    }

    public function current()
    {
        return current($this->_data);
    }

    /**
     * 返回该对象的数组形式
     *
     * @return 返回该对象的数组形式
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * 将数组加入该对象中
     *
     * @param array $data 要加入对象的数组
     * @return object 当前对象
     */
    public function fromArray($data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * 合并两个元数据
     *
     * @param Qwin_Metadata_Abstract $meta
     * @return object 当前对象
     */
    public function merge(Qwin_Metadata_Abstract $meta)
    {
        $this->_data += $meta->toArray();
        return $this;
    }
}
