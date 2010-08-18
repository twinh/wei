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
                '_type' => null,
                '_typeExt' => null,
                '_resource' => null,
                '_resourceGetter' => null,
                '_icon' => null,
                '_value' => '',
                'name' => null,
                'id' => null,
            ),
            'attr' => array(
                'isListLink' => 0,
                'isList' => 1,
                'isSqlField' => 1,
                'isSqlQuery' => 1,
                'isReadonly' => 0,
                'isShow' => 1,
            ),
            'converter' => array(
                'add' => null,
                'edit' => null,
                'list' => null,
                'db' => null,
            ),
            'validator' => array(

            ),
        );
    }

    public function format()
    {
        return $this->_formatAsArray();
    }

    protected function _format($metadata)
    {
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
}
