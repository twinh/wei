<?php
/**
 * Field
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
 * @since       2010-7-26 14:07:07
 */

class Qwin_Metadata_Element_Field extends Qwin_Metadata_Element_Driver
{
   /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    /**
     * 默认数据
     * @var array
     */
    protected $_default = array(
        'basic' => array(
            'title' => 'FLD_TITLE',
            'description' => array(),
            'order' => 50,
            'group' => 0,
        ),
        'form' => array(
            '_type' => 'text',
            '_resource' => null,
            '_value' => '',
            'name' => null,
//            '_resourceGetter' => null,
//            '_resourceFormFile' => null,
//            '_widget' => array(),
//            '_extend' => array(),
//            'id' => null,
//            'class' => null,
        ),
        'attr' => array(
            'isLink' => 0,
            'isList' => 0,
            'isDbField' => 1,
            'isDbQuery' => 1,
            'isReadonly' => 0,
            'isView' => 1,
        ),
//        'db' => array(
//            'type' => 'string',
//            'length' => null,
//        ),
//        'sanitiser' => array(),
//        'validator' => array(
//            'rule' => array(),
//            'message' => array(),
//        ),
    );

    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $option 选项
     * @return Qwin_Metadata_Element_Field 当前对象
     */
    public function merge($data, array $option = array())
    {
        $data = $this->_mergeAsArray($data, $option);
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }

    /**
     * 格式化数据
     *
     * @param array $data 数据
     * @param string $name 名称
     * @return Qwin_Metadata_Element_Field 当前对象
     */
    protected function _merge($data, array $option = array(), $name = null)
    {
        $data = (array)$data;
        if (!isset($data['form'])) {
            $data['form'] = array();
        }
        if (!isset($data['form']['name'])) {
            if (null != $name) {
                $data['form']['name'] = $name;
            } else {
                require_once 'Qwin/Metadata/Element/Field/Exception.php';
                throw new Qwin_Metadata_Element_Field_Exception('The name value is not defined.');
            }
        }

        if (!isset($data['basic'])) {
            $data['basic'] = array();
        }
        
        // 设置名称
        if (!isset($data['basic']['title'])) {
            $data['basic']['title'] = 'FLD_' . strtoupper($data['form']['name']);
        }
        // 设置编号
        if (!isset($data['form']['id'])) {
            $data['form']['id'] = $data['form']['name'];
        }

        // 初始验证器和补全验证信息
        if(isset($data['validator']) && !empty($data['validator']['rule'])) {
            foreach ($data['validator']['rule'] as $key => $rule) {
                if (!isset($data['validator']['message'][$key])) {
                    $data['validator']['message'][$key] = 'VLD_' . strtoupper($key);
                }
            }
        }

        // 转换转换器的配置,使不同的行为之间允许共享转换器
        if (isset($data['sanitiser'])) {
            foreach ((array)$data['sanitiser'] as $key => $value) {
                if (is_string($value) && isset($data['sanitiser'][$value])) {
                    $data['sanitiser'][$key] = $data['sanitiser'][$value];
                }
            }
        }
        
        foreach ($this->_default as $key => $row) {
            if (isset($data[$key])) {
                $data[$key] = array_merge($row, $data[$key]);
            } else {
                 $data[$key] = $row;
            }
        }
        return $data;
    }

    /**
     * 筛选符合属性的域
     *
     * @param 合法的属性组成的数组
     * @param 非法的属性组成的数组
     * @return array 符合要求的的域组成的数组
     */
    public function getAttrList($allowAttr, $banAttr = null)
    {
        $allowAttr = (array)$allowAttr;
        $banAttr = (array)$banAttr;

        // 查找是否已有该属性的缓存数据
        $cacheName = implode('|', $allowAttr) . '-' . implode('', $banAttr);
        if (isset($this->_attrCache[$cacheName])) {
            return $this->_attrCache[$cacheName];
        }

        $tmpArr = array();
        $result = array();
        foreach ($allowAttr as $attr) {
            $tmpArr[$attr] = 1;
        }
        foreach ($banAttr as $attr) {
            $tmpArr[$attr] = 0;
        }
        foreach ($this as $field) {
            if ($tmpArr == array_intersect_assoc($tmpArr, $field['attr'])) {
                $result[$field['form']['name']] = $field['form']['name'];
            }
        }
        // 加入缓存中
        $this->_attrCache[$cacheName] = $result;
        return $result;
    }

    public function setField($name, $data)
    {
        $this->_data[$name] = $this->_multiArrayMerge($this->_data[$name], $data);
        return $this;
    }

    /**
     * 设置指定域的属性
     *
     * @param string $field 域的名称
     * @param string $attr 属性的名称
     * @param mixed $value 属性的值
     * @return Qwin_Metadata_Element_Field 当前对象
     */
    public function setAttr($field, $attr, $value)
    {
        $this[$field]['attr'][$attr] = $value;
        return $this;
    }

    /**
     * 根据域中的order从小到大排序
     * 
     * @return Qwin_Metadata_Element_Field 当前对象
     * @todo 转为n维数组排序
     */
    public function order()
    {
        $newArr = array();
        foreach ($this->_data as $key => $val) {
            $tempArr[$key] = $val['basic']['order'];
        }
        // 倒序再排列,因为 asort 会使导致倒序
        $tempArr = array_reverse($tempArr);
        asort($tempArr);
        foreach ($tempArr as $key => $val) {
            $newArr[$key] = $this->_data[$key];
        }
        $this->_data = $newArr;
        return $this;
    }
    
    /**
     * 增加表单域的类名
     *
     * @param string $field 域的名称
     * @param string $value 类名,多个类名用空格分开
     * @return object 当前对象
     * @todo [重要]象数组一样自由赋值
     */
    public function addClass($field, $value)
    {
        if ('' != $this->_data[$field]['form']['class']) {
            $value = ' ' . $value;
        }
        $this->_data[$field]['form']['class'] .= $value;
        return $this;
    }

    public function getSecondLevelValue($type)
    {
        $newData = array();
        foreach ($this->_data as $data) {
            $newData[$data['form']['name']] = $data[$type[0]][$type[1]];
        }
        return $newData;
    }
    
    /**
     * 设置指定域为只读
     *
     * @param array|string $data
     * @return object 当前对象
     */
    public function setReadonly($data)
    {
        $data = (array)$data;
        foreach ($data as $key) {
            if (0 == $this->_data[$key]['attr']['isReadonly']) {
                $this->_data[$key]['attr']['isReadonly'] = 1;
                $this->_data[$key]['form']['_type'] = 'hidden';
            }
        }
        return $this;
    }

    /**
     * 为元数据表单名称增加组名,如name将转换为group[name]
     *
     * @param string $name 组名
     * @return object 当前对象
     */
    public function setFormGroupName($name)
    {
        foreach ($this->_data as $key => $value) {
            $this->_data[$key]['form']['_oldName'] = $this->_data[$key]['form']['_name'];
            $this->_data[$key]['form']['id'] = $name . '_' . $this->_data[$key]['form']['_name'];
            $this->_data[$key]['form']['_name'] = $name . '[' . $this->_data[$key]['form']['_name'] . ']';
        }
        return $this;
    }

     /**
     * 为元数据表单名称增加前缀
     *
     * @param string $name 前缀
     * @return object 当前对象
     */
    public function setFormPrefixName($name)
    {
        foreach ($this->_data as $key => $value) {
            $this->_data[$key]['form']['_oldName'] = $this->_data[$key]['form']['_name'];
            $this->_data[$key]['form']['_name'] = $name . $this->_data[$key]['form']['_name'];
        }
        return $this;
    }

    /**
     * 设置除了参数中定义的键名外为只读
     *
     * @param array|string $data
     * @return object 当前对象
     * @todo 通过php数组函数优化
     */
    public function setReadonlyExcept($data)
    {
        $data = (array)$data;
        foreach ($this->_data as $key => $value) {
            if (!in_array($key, $data) && 0 == $this->_data[$key]['attr']['isReadonly']) {
                $this->_data[$key]['attr']['isReadonly'] = 1;
                $this->_data[$key]['form']['_type'] = 'hidden';
            }
        }
        return $this;
    }

    /**
     * 获取表单配置中的初始值
     *
     * @return array
     */
    public function getFormValue()
    {
        $data = array();
        foreach ($this as $name => $field) {
            $data[$name] = $field['form']['_value'];
        }
        return $data;
    }
}
