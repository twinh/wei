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

abstract class Qwin_Metadata_Abstract extends ArrayObject
{
    /**
     * 管理类
     *
     * @var Qwin_Metadata
     */
    protected $_manager = null;

    /**
     * 初始化类
     *
     * @param array $input 数组
     */
    public function  __construct($input = array(), $flags = self::ARRAY_AS_PROPS)
    {
        parent::__construct($input, $flags);
    }

    /**
     * 获取管理器对象
     *
     * @return Qwin_Metadata
     */
    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * 设置管理器
     *
     * @param Qwin_Metadata $metadata 管理器对象
     * @return Qwin_Metadata_Abstract 当前对象
     */
    public function setManager(Qwin_Metadata $metadata)
    {
        $this->_manager = $metadata;
        return $this;
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
    public function fromArray(array $data)
    {
        $this->exchangeArray(array_merge($this->getArrayCopy(), $data));
        return $this;
    }

    public function appendMetadata()
    {

    }

    public function setMetadata()
    {

    }

    public function merge(array $data, $name = null)
    {
        // 补全数据
        if (isset($name)) {
            $data  = array(
                $name => $data,
            );
        }

        // 获取转换驱动数组
        $drivers = Qwin_Metadata::getInstance()->getDriver();

        $result = array();
        foreach ($data as $name => $value) {
            // 检查是否存在该元素的驱动类
            $name = strtolower($name);

            // 初始化驱动类
            if (!isset($this[$name]) && isset($drivers[$name])) {
                $this[$name] = new $drivers[$name];
            }

            // 没有找到驱动类,为纯数组形式
            if (!isset($this[$name])) {
                $this[$name] = array();
            }

            // 根据不同类型合并数据
            if (is_object($this[$name])) {
                $this[$name]->merge($value);
            } else {
                $this[$name] = $value + $this[$name];
            }
        }
        return $this;
    }

    public function getId()
    {
        if (!isset($this->_id)) {
            if (isset($this['db']['table'])) {
                $this->_id = $this['db']['table'];
            } else {
                $this->_id = strtolower(get_class($this));
            }
        }
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = (string)$id;
        return $this;
    }
}
