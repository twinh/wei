<?php
/**
 * Metadata
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
 * @since       2010-7-26 13:14:28
 *
 */

/**
 * @see Qwin_Metadata_AccessArray
 */
require_once 'Qwin/Metadata/AccessArray';

abstract class Qwin_Metadata extends Qwin_Metadata_AccessArray
{
    /**
     * 原始数据,未经过格式转换
     * @var array
     */
    private $_originalData;

    /**
     * 经过格式化转换的数据
     * @var array
     */
    protected $_data;

    /**
     * 不允许的元数据键名
     * @var array
     */
    private $_banType = array('Abstract', 'Interface', 'Driver');

    /**
     * 设置元数据
     *
     * @return object 当前对象
     */
    public function setMetadata()
    {
        return $this;
    }

    /**
     * 添加一组元数据
     * 
     * @param array $metadata
     */
    public function addAll($metadata)
    {
        foreach($metadata as $key => $row)
        {
            $type = ucfirst(strtolower($key));
            $this->_add($type, $row);
        }
    }


    /**
     * 为添加数据到元数据的某一类型中
     *
     * @param string $type
     * @param mixed $data
     */
    private function _add($type, $data, $overwrite = false)
    {
        if(!in_array($type, $this->_banType))
        {
            $name = strtolower($type);
            $class = 'Qwin_Metadata_Element_' . $type;
            if(class_exists($class))
            {
                // 加入到原始数据中
                //!isset($this->_originalData[$name]) && $this->_originalData[$name] = array();
                // 加入到数据中
                if(!isset($this->_data[$name]))
                {
                    $this->_data[$name] = new $class;
                }
                if(false == $overwrite)
                {
                    //$this->_originalData[$name] += $data;
                    // TODO 仅对新数据进行格式化
                    $this->_data[$name]->addData($data)->format();
                } else {
                    //$this->_originalData[$name] = $data;
                    $this->_data[$name]->setData($data)->format();
                }
            }
        }
    }

    /**
     * 魔术方法,调用add,get,set三类方法
     *
     * @param <type> $name
     * @param <type> $args
     * @return <type>
     */
    public function  __call($name, $args)
    {
        $action = substr($name, 0, 3);
        $type = substr($name, 3);
        if('add' == $action)
        {
            $this->_add($type, $args[0]);
        } elseif('get' == $action) {
            return $this->_data[strtolower($type)];
        } elseif('set' == $actiob) {
            $this->_set($type, $args[0]);
        }
    }
}
