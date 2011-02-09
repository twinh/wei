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
 * @since       2010-07-27 15:41:46
 */

abstract class Qwin_Metadata_Abstract implements ArrayAccess, Iterator
{
    protected $_data = array();

    /**
     * 通过魔术方法设置一个属性的值
     *
     * @param string $name 名称
     * @param mixed $value 值
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * 通过魔术方法获取一个属性的值
     *
     * @param string $name 名称
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * 检查索引是否存在
     *
     * @param string $offset 索引
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * 获取索引的数据
     *
     * @param string $offset 索引
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

    /**
     * 设置索引的值
     *
     * @param string $offset 索引
     * @param mixed $value 值
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    /**
     * 销毁一个索引
     *
     * @param string $offset 索引的名称
     */
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
        return false !== $this->current();
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
        return $this->_toArray($this->_data);
    }


    protected function _toArray($data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            if (is_object($value) && is_subclass_of($value, __CLASS__)) {
                $result[$key] = $this->_toArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
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
