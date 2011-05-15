<?php
/**
 * Fields
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
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-26 14:07:07
 */

class Qwin_Meta_Fields extends Qwin_Meta_Common
{
   /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    /**
     * @var array $_defaults        默认选项
     *
     *      -- title                标题标识, 默认为 FLD_$fieldUppeName
     * 
     *      -- description          域描述
     * 
     *      -- order                排序
     * 
     *      -- dbField              是否为数据库字段
     * 
     * 
     *
     *      -- null                 是否将NULL字符串转换为null类型
     *
     *      -- type                 是否进行强类型的转换,类型定义在['fieldName]['db']['type']
     *
     *      -- meta                 是否使用元数据的sanitise配置进行转换
     *
     *      -- sanitise             是否使用转换器进行转换
     *
     *      -- relatedMeta          是否转换关联的元数据
     */
    protected $_defaults = array(
        'name' => null,
        'title' => null,
        'description' => array(),
        'order' => 50,
        'dbField' => true,
        'dbQuery' => true,
        'urlQuery' => true,
        'readonly' => false,
//        'db' => array(
//            'type' => 'string',
//            'length' => null,
//        ),
    );

    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $option 选项
     * @return Qwin_Meta_Field 当前对象
     */
    public function merge($data, array $options = array())
    {
        $data = $this->_mergeAsArray((array)$data, $options);
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }

    /**
     * 格式化数据
     *
     * @param array $data 数据
     * @param string $name 名称
     * @return Qwin_Meta_Field 当前对象
     */
    protected function _merge($name, $data, array $options = array())
    {
        !is_array($data) && $data = (array)$data;
        
        // 名称需一致
        $data['name'] = $name;
        
        // 设置名称
        if (!isset($data['title'])) {
            $data['title'] = 'FLD_' . strtoupper($name);
        }
        
        $data = $data + $this->_defaults;

//        foreach ($this->_defaults as $key => $row) {
//            if (!array_key_exists($key, $data)) {
//                $data[$key] = $row;
//            } elseif (is_array($data[$key])) {
//                $data[$key] = array_merge($row, $data[$key]);
//            }
//        }
        
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
     * @return Qwin_Meta_Field 当前对象
     */
    public function setAttr($field, $attr, $value)
    {
        $this[$field]['attr'][$attr] = $value;
        return $this;
    }

    /**
     * 根据域中的order从小到大排序
     * 
     * @return Qwin_Meta_Field 当前对象
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
        if (isset($this->{$field}['form']['class'])) {
            $this->{$field}['form']['class'] = $this->{$field}['form']['class'] . ' ' . $value;
        } else {
            $this->{$field}['form']['class'] = $value;
        }
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

    public function getResource($field)
    {
        if (isset($this[$field])) {
            if ($this[$field]['form']['_resource']) {
                return $this[$field]['form']['_resource'];
            } else {
                if (isset($this[$field]['form']['_resourceGetter'])) {
                    $resource = Qwin::call('-flow')->callOne($this[$field]['form']['_resourceGetter']);
                } else {
                    return array();
                }

                // 认定为选项模块的选项
                $element = $resource[key($resource)];
                if (is_array($element)) {
                    $this[$field]['form']['_resource'] = $resource;
                } else {
                    // 否则,认定为value=>name的形式
                    $return = array();
                    foreach($resource as $value => $name) {
                        $return[$value] = array(
                            'value' => $value,
                            'name' => $name,
                            'color' => null,
                            'style' => null,
                        );
                    }
                    $this[$field]['form']['_resource'] = $return;
                }

                return $this[$field]['form']['_resource'];
            }
        }
        throw new Qwin_Meta_Field_Exception('Undefined field "' . $field . '"');
    }
}
