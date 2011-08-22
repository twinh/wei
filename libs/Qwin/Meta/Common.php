<?php
/**
 * Driver
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
 * @since       2011-02-26 14:29:27
 * @method mixed get*(mixed $value) magic finders; @see __call()
 * @method mixed set*(mixed $value) magic finders; @see __call()
 */

class Qwin_Meta_Common extends ArrayObject implements Qwin_Meta_Interface
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array();

    /**
     * 选项
     * @var array
     */
    protected $_options = array();
    
    /**
     * 元数据父对象
     * 
     * @var Qwin_Meta_Abstract
     */
    protected $_parent;

    /**
     * 初始化类
     *
     * @param array $array 数组
     */
    public function  __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    /**
     * 格式化数据,例如,设置默认数据,为未初始化的键名设置空值
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Meta_Common 当前对象
     * @todo 是否一定要数组
     */
    public function merge($data, array $options = array())
    {
        $data = (array)$data + $this->_defaults;
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }

    /**
     * 以数组的形式格式化数据
     *
     * @return array 格式过的数据
     */
    protected function _mergeAsArray($data, array $options = array())
    {
        foreach ($data as $name => &$row) {
            $row = $this->_merge($name, $row, $options);
        }
        return $data;
    }

    /**
     * 格式化数据
     *
     * @return array 格式过的数据
     */
    protected function _merge($name, $data, array $options = array())
    {
        return array_merge($this->_defaults, $data);
    }

    /**
     * 获取选项
     *
     * @param string $name 选项名称
     * @return mixed 选项值
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_options;
        }
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }
    
    /**
     * 获取默认选项
     * 
     * @param string $name 选项名称
     * @return mixed 选项值
     */
    public function getDefault($name = null)
    {
        if (null == $name) {
            return $this->_defaults;
        }
        return isset($this->_defaults[$name]) ? $this->_defaults[$name] : null;
    }

    /**
     * 合并多维数组
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @todo 深度,效率
     */
    protected function _multiArrayMerge($array1, $array2)
    {
        foreach($array2 as $key => $val) {
            if (is_array($val)) {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->_multiArrayMerge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }
    
    /**
     * 获取元数据数组中,键名为$key,值为$value的数据内容
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return array 数组
     * @todo 缓存
     */
    public function getBy($key, $value)
    {
        $result = array();
        foreach ($this as $name => $field) {
            if (isset($field[$key]) && $field[$key] === $value) {
                $result[$name] = true;
            }
        }
        return $result;
    }
    
    /**
     * 获取元数据$name键名中,键名为$key,值为$value的数据内容
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @param string $name 元数据键名
     * @return array 数组
     * @todo 缓存
     * @todo 高级筛选
     */
    public function getByField($key, $value, $name = 'fields')
    {
        if (!isset($this[$name])) {
            throw new Qwin_Meta_Common_Exception('Undefiend index "' . $name . '" in metadata ' . get_class($this));
        }
        $result = array();
        foreach ($this[$name] as $name => $field) {
            if (isset($field[$key]) && $field[$key] === $value) {
                $result[$name] = true;
            }
        }
        return $result;
    }
    
    /**
     * 设置父元数据对象,方便调用
     * 
     * @param Qwin_Meta_Abstract $meta 父元数据对象
     * @return Qwin_Meta_Common 当前对象
     */
    public function setParent(Qwin_Meta_Abstract $meta)
    {
        foreach ($meta as $element => $value) {
            if ($value === $this) {
                $this->_parent = $meta;
                return $this;
            }
        }
        throw new Qwin_Meta_Common_Exception('Sub meta not found.');
    }
    
    /**
     * 获取父元数据对象
     * 
     * @return Qwin_Meta_Abstract
     */
    public function getParent()
    {
        // 在一些环境中,返回结果为isset false
        if (null !== $this->_parent) {
        //if (isset($this->_parent)) {
            return $this->_parent;
        }
        throw new Qwin_Meta_Common_Exception('Parent meta not defined.');
    }
    
    /**
     * 魔术方法,允许通过getXXX取值和setXXX设置值
     * 建议直接使用$this[$name],甚至是$this->offsetGet($name)和
     * $this->offsetSet($name)获得更好的效率
     * 
     * @param string $name 方法名称
     * @param array $arguments 参数数组
     * @return mixed 结果
     */
    public function __call($name, $arguments)
    {
        $lname = strtolower($name);
        $action = substr($lname, 0, 3);
        $element = substr($lname, 3);
        
        if ('get' == $action) {
            if ($this->offsetExists($element)) {
                return $this->offsetGet($element);
            }
        } elseif ('set' == $action) {
            if (0 === count($arguments)) {
                throw new Qwin_Meta_Common_Exception('You must specify the value to ' . $name);
            }
            return $this->offsetSet($element, $arguments[0]);
        }
        throw new Qwin_Meta_Common_Exception('Call to undefined method "' . get_class($this) .  '::' . $name . '()".');
    }
}
