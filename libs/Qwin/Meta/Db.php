<?php
/**
 * Db
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
 * @since       2010-07-27 18:13:16
 */

class Qwin_Meta_Db extends Qwin_Meta_Common
{
    /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    /**
     * @var array $_defaults        默认选项
     * 
     *      -- name                 名称
     *
     *      -- title                标题标识, 默认为 FLD_$fieldUppeName
     * 
     *      -- description          域描述
     * 
     *      -- order                排序
     * 
     *      -- dbField              是否为数据库字段
     * 
     *      -- dbQuery              是否允许数据库查询
     * 
     *      -- urlQuery             是否允许Url查询
     * 
     *      -- readonly             是否只读
     */
    protected $_fieldDefaults = array(
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
     * 默认选项
     * @var array 
     */
    protected $_defaults = array(
        'fields' => array(),
        //'type' => 'sql',
        'table' => null,
        'id' => 'id',
        'offset' => 0,
        'limit' => 10,
        'order' => array(),
        'where' => array(),
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
        $data = (array)$data + $this->_defaults;
        !is_array($data['fields']) && (array)$data['fields'];
                
        // 处理通配选项
        if (array_key_exists('*', $data['fields'])) {
            $this->_fieldDefaults = $this->_fieldDefaults + (array)$data['fields']['*'];
            unset($data['fields']['*']);
        }
        
        foreach ($data['fields'] as $name => &$field) {
            !isset($field['name']) && $field['name'] = 'FLD_' . strtoupper($name);
            $field = (array)$field + $this->_fieldDefaults;
        }
        $this->exchangeArray($data);
        return $this;
    }
    
//    public function setField($name, $data)
//    {
//        $this->_data[$name] = $this->_multiArrayMerge($this->_data[$name], $data);
//        return $this;
//    }
//
//    /**
//     * 设置指定域的属性
//     *
//     * @param string $field 域的名称
//     * @param string $attr 属性的名称
//     * @param mixed $value 属性的值
//     * @return Qwin_Meta_Field 当前对象
//     */
//    public function setAttr($field, $attr, $value)
//    {
//        $this[$field]['attr'][$attr] = $value;
//        return $this;
//    }
//    
//    /**
//     * 增加表单域的类名
//     *
//     * @param string $field 域的名称
//     * @param string $value 类名,多个类名用空格分开
//     * @return object 当前对象
//     * @todo [重要]象数组一样自由赋值
//     */
//    public function addClass($field, $value)
//    {
//        if (isset($this->{$field}['form']['class'])) {
//            $this->{$field}['form']['class'] = $this->{$field}['form']['class'] . ' ' . $value;
//        } else {
//            $this->{$field}['form']['class'] = $value;
//        }
//        return $this;
//    }
//    
//    /**
//     * 设置指定域为只读
//     *
//     * @param array|string $data
//     * @return object 当前对象
//     */
//    public function setReadonly($data)
//    {
//        $data = (array)$data;
//        foreach ($data as $key) {
//            if (0 == $this->_data[$key]['attr']['isReadonly']) {
//                $this->_data[$key]['attr']['isReadonly'] = 1;
//                $this->_data[$key]['form']['_type'] = 'hidden';
//            }
//        }
//        return $this;
//    }
//
//     /**
//     * 为元数据表单名称增加前缀
//     *
//     * @param string $name 前缀
//     * @return object 当前对象
//     */
//    public function setFormPrefixName($name)
//    {
//        foreach ($this->_data as $key => $value) {
//            $this->_data[$key]['form']['_oldName'] = $this->_data[$key]['form']['_name'];
//            $this->_data[$key]['form']['_name'] = $name . $this->_data[$key]['form']['_name'];
//        }
//        return $this;
//    }
//
//    /**
//     * 设置除了参数中定义的键名外为只读
//     *
//     * @param array|string $data
//     * @return object 当前对象
//     * @todo 通过php数组函数优化
//     */
//    public function setReadonlyExcept($data)
//    {
//        $data = (array)$data;
//        foreach ($this->_data as $key => $value) {
//            if (!in_array($key, $data) && 0 == $this->_data[$key]['attr']['isReadonly']) {
//                $this->_data[$key]['attr']['isReadonly'] = 1;
//                $this->_data[$key]['form']['_type'] = 'hidden';
//            }
//        }
//        return $this;
//    }
//
//    /**
//     * 获取表单配置中的初始值
//     *
//     * @return array
//     */
//    public function getFormValue()
//    {
//        $data = array();
//        foreach ($this as $name => $field) {
//            $data[$name] = $field['form']['_value'];
//        }
//        return $data;
//    }

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
