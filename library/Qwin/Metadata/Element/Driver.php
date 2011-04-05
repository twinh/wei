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
 */

abstract class Qwin_Metadata_Element_Driver extends ArrayObject implements Qwin_Metadata_Element_Interface
{
    /**
     * 默认数据
     * @var array
     */
    protected $_defaults = array();

    /**
     * 选项
     * @var array
     */
    protected $_options = array();

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
     * @return Qwin_Metadata_Element_Driver 当前对象
     */
    public function merge($data, array $options = array())
    {
        $data = $data + $this->_defaults;
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
        foreach ($data as $key => &$row) {
            $row = $this->_merge($row, $options, $key);
        }
        return $data;
    }

    /**
     * 格式化数据
     *
     * @return array 格式过的数据
     */
    protected function _merge($data, array $options = array(), $name = null)
    {
        return array_merge($this->_defaults, $data);
    }

    /**
     * 获取选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getoption($name = null)
    {
        if (null == $name) {
            return $this->_options;
        }
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }

    /**
     * 合并多维数组
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @todo 深度
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
}