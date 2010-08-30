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

class Qwin_Metadata_Element_Field extends Qwin_Metadata_Element_Abstract
{
    /**
     * 排序的大小,用于自动生成排序值
     * @var int
     */
    protected $_order = 0;

    /**
     * 排序的每次递增的数量
     * @var int
     */
    protected $_orderLength = 20;

    /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    public function getSampleData()
    {
        return array(
            'basic' => array(
                'title' => 'LBL_FIELD_TITLE',
                'description' => array(),
                'order' => 0,
                'group' => 'LBL_GROUP_BASIC_DATA',
            ),
            'form' => array(
                '_type' => 'text',
                '_typeExt' => null,
                '_resource' => null,
                '_resourceGetter' => null,
                //'_resourceFormFile' => null,
                '_widget' => null,
                '_widgetDetail' => array(),
                '_value' => '',
                'name' => null,
                'id' => null,
                'class' => null,
            ),
            'attr' => array(
                'isListLink' => 0,
                'isList' => 1,
                'isDbField' => 1,
                'isDbQuery' => 1,
                'isReadonly' => 0,
                'isView' => 1,
            ),
            'converter' => array(
                'add' => null,
                'edit' => null,
                'list' => null,
                'db' => null,
                'view' => null,
            ),
            'validator' => array(

            ),
        );
    }

    public function format()
    {
        return $this->_formatAsArray();
    }

    protected function _format($metadata, $name = null)
    {
        // 转换成数组
        if(is_string($metadata))
        {
            $metadata = array(
                'form' => array(
                    'name' => $metadata,
                )
            );
        // 初始化名称
        } else {
            if(!isset($metadata['form']))
            {
                $metadata['form'] = array();
            }
            if(!isset($metadata['form']['name']))
            {
                if(null != $name)
                {
                    $metadata['form']['name'] = $name;
                } else {
                    require_once 'Qwin/Metadata/Element/Field/Exception.php';
                    throw new Qwin_Metadata_Element_Field_Exception('The name value is not defined.');
                }
            }
        }

        if(!isset($metadata['basic']))
        {
            $metadata['basic'] = array();
        }

        // 设置名称
        if(!isset($metadata['basic']['title']))
        {
            $metadata['basic']['title'] = 'LBL_FIELD_' . strtoupper($metadata['form']['name']);
        }

        // 设置描述语句
        if(!isset($metadata['basic']['description']))
        {
            $metadata['basic']['description'] = array();
        }
        elseif(!is_array($metadata['basic']['description']))
        {
            $metadata['basic']['description'] = array($metadata['basic']['description']);
        }

        // 设置排序
        if(!isset($metadata['basic']['order']))
        {
            $metadata['basic']['order'] = $this->_order;
            $this->_order += $this->_orderLength;
        } else {
            $metadata['basic']['order'] = (int)$metadata['basic']['order'];
        }

        // 设置编号
        if(!isset($metadata['form']['id']))
        {
            $metadata['form']['id'] = $metadata['form']['name'];
        }

        // 转换按钮
        $metadata['form'] = $this->_parseWidgetSetting($metadata['form']);
        
        return $this->_multiArrayMerge($this->getSampleData(), $metadata);
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
        if(isset($this->_attrCache[$cacheName]))
        {
            return $this->_attrCache[$cacheName];
        }

        $tmpArr = array();
        $result = array();
        foreach($allowAttr as $attr)
        {
            $tmpArr[$attr] = 1;
        }
        foreach($banAttr as $attr)
        {
            $tmpArr[$attr] = 0;
        }
        foreach($this->_data as $field)
        {
            if($tmpArr == array_intersect_assoc($tmpArr, $field['attr']))
            {
                $result[$field['form']['name']] = $field['form']['name'];
            }
        }
        // 加入缓存中
        $this->_attrCache[$cacheName] = $result;
        return $result;
    }

    /**
     * 设置指定域的属性
     *
     * @param string $field 域的名称
     * @param string $attr 属性的名称
     * @param mixed $value 属性的值
     * @return Qwin_Metadata_Element_Field 当前类
     */
    public function setAttr($field, $attr, $value)
    {
        $this->_data[$field]['attr'][$attr] = $value;
        return $this;
    }

    /**
     * 根据域中的order从小到大排序
     * 
     * @return Qwin_Metadata_Element_Field 当前类
     * @todo 转为n维数组排序
     */
    public function order()
    {
        $newArr = array();
        foreach($this->_data as $key => $val)
        {
            $tempArr[$key] = $val['basic']['order'];
        }
        // 倒序再排列,因为 asort 会使导致倒序
        $tempArr = array_reverse($tempArr);
        asort($tempArr);
        foreach($tempArr as $key => $val)
        {
            $newArr[$key] = $this->_data[$key];
        }
        $this->_data = $newArr;
        return $this;
    }

    public function addValidator()
    {

    }

    public function addValidatorRule()
    {

    }

    /**
     * 转换语言
     *
     * @param array $language 用于转换的语言
     * @return Qwin_Metadata_Element_Field 当前类
     */
    public function translate($language)
    {
        
        foreach($this->_data as &$data)
        {
            // 转换标题
            $data['basic']['titleCode'] = $data['basic']['title'];
            if(isset($language[$data['basic']['title']]))
            {
                $data['basic']['title'] = $language[$data['basic']['title']];
            }

            // 转换描述
            $data['basic']['descriptionCode'] = array();
            foreach($data['basic']['description'] as $key => &$description)
            {
                $data['basic']['descriptionCode'][$key] = $description;
                if(isset($language[$description]))
                {
                    $description = $language[$description];
                }
            }

            // 转换分组
            $data['basic']['groupCode'] = $data['basic']['group'];
            if(isset($language[$data['basic']['group']]))
            {
                $data['basic']['group'] = $language[$data['basic']['group']];
            }
        }
        return $this;
    }

    /**
     * 获取分组的对应列表
     *
     * @return array 分组列表
     */
    public function getGroupList()
    {
        $groupList = array();
        foreach($this->_data as $field => $data)
        {
            if(!isset($groupList[$data['basic']['group']]))
            {
                $groupList[$data['basic']['group']] = array();
            }
            $groupList[$data['basic']['group']][$field] = $data['basic']['title'];
        }
        return $groupList;
    }

    /**
     * 获取显示数据页面的分组列表
     *
     * @return array 分组列表
     */
    public function getViewGroupList()
    {
        $groupList = array();
        foreach($this->_data as $field => $data)
        {
            if(0 == $data['attr']['isView'])
            {
                continue;
            }

            if(!isset($groupList[$data['basic']['group']]))
            {
                $groupList[$data['basic']['group']] = array();
            }
            $groupList[$data['basic']['group']][$field] = $data['basic']['title'];
        }
        return $groupList;
    }

    /**
     * 获取添加操作的分组列表
     *
     * @return array 分组列表
     */
    public function getAddGroupList()
    {
        $groupList = array();
        foreach($this->_data as $field => $data)
        {
            if('custom' == $data['form']['_type'])
            {
                continue;
            }

            if(!isset($groupList[$data['basic']['group']]))
            {
                $groupList[$data['basic']['group']] = array();
            }
            $groupList[$data['basic']['group']][$field] = $data['basic']['title'];
        }
        return $groupList;
    }

    /**
     * 获取编辑操作的分组列表
     *
     * @return array 分组列表
     */
    public function getEditGroupList()
    {
        $groupList = array();
        foreach($this->_data as $field => $data)
        {
            if(1 == $data['attr']['isReadonly'] || 'custom' == $data['form']['_type'])
            {
                $this->_data[$field]['form']['_type'] = 'hidden';
                //continue;
            }

            if(!isset($groupList[$data['basic']['group']]))
            {
                $groupList[$data['basic']['group']] = array();
            }
            $groupList[$data['basic']['group']][$field] = $data['basic']['title'];
        }
        return $groupList;
    }
    
    /**
     * 增加表单域的类名
     *
     * @param string $field 域的名称
     * @param string $value 类名,多个类名用空格分开
     * @return object 当前类
     * @todo [重要]象数组一样自由赋值
     */
    public function addClass($field, $value)
    {
        if('' != $this->_data[$field]['form']['class'])
        {
            $value = ' ' . $value;
        }
        $this->_data[$field]['form']['class'] .= $value;
        return $this;
    }

    /**
     * 微件缩写的对应列表
     * @var array
     * @todo 微件
     */
    protected $_widgetMap = array(
        'fileTree' => 'Qwin_Widget_JQuery_FileTree',
        'ajaxUpload' => 'Qwin_Widget_JQuery_AjaxUpload',
        'CKEditor' => 'Qwin_Widget_Editor_CKEditor',
        'datepicker' => 'Qwin_Widget_JQuery_Datepicker'
    );

    /**
     * 将微件配置转换为完整配置
     *
     * @param array $form
     * @return array
     */
    public function _parseWidgetSetting($form)
    {
        !isset($form['_widgetDetail']) && $form['_widgetDetail'] = array();
        if(isset($form['_widget']))
        {
            $newSetting = array();
            !is_array($form['_widget']) && $form['_widget'] = array($form['_widget']);
            foreach($form['_widget'] as $name => $setting)
            {
                isset($this->_widgetMap[$setting]) && $setting = $this->_widgetMap[$setting];
                /**
                 * 默认的生成方法是render
                 */
                $newSetting[] = array(
                    array($setting, 'render')
                );
            }
            $form['_widgetDetail'] = array_merge($form['_widgetDetail'], $newSetting);
        }
        return $form;
    }

    public function getSecondLevelValue($type)
    {
        $newData = array();
        foreach($this->_data as $data)
        {
            $newData[$data['form']['name']] = $data[$type[0]][$type[1]];
        }
        return $newData;
    }

    /**
     * 获取添加操作入库的域对象
     *
     * @return Qwin_Metadata_Element_Field 域对象
     */
    public function getAddDbField()
    {
        foreach($this->_data as $field => $meta)
        {
            if(1 == $meta['attr']['isDbField'])
            {
                $fieldMetadata[$field] = $meta;
            }
        }
        $fieldObejct = new Qwin_Metadata_Element_Field();
        return $fieldObejct->fromArray($fieldMetadata);
    }
}
