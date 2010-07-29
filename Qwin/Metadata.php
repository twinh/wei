<?php
/**
 * Metadata
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-7-26 13:14:28
 * @since     2010-7-26 13:14:28
 */

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
     * 
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
