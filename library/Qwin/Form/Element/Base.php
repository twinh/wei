<?php
/**
 * Base
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
 * @subpackage  Form
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 * @todo        分离等
 */

require_once 'Qwin/Form.php';

class Qwin_Form_Element_Base extends Qwin_Form
{
    public function plain($publicSet, $privateSet, $value)
    {
        return $value;
    }

    /**
     * 创建文本域
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function text($publicSet, $privateSet, $value)
    {
        $set_addition = array(
            'type' => 'text',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $publicSet);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    public function file($publicSet, $privateSet, $value)
    {
        $set_addition = array(
            'type' => 'file',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $publicSet);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建密码域
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function password($publicSet, $privateSet, $value)
    {
        $set_addition = array(
            'type' => 'password',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $publicSet);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建隐藏域
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function hidden($publicSet, $privateSet, $value)
    {
        $set_addition = array(
            'type' => 'hidden',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $publicSet);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建文本区域
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function textarea($publicSet, $privateSet, $value)
    {
        $attr = parent::_getAttr($publicSet);
        $data = '<textarea ' . $attr . '>' . $value . '</textarea>';
        return $data;
    }

    /**
     * 创建多选按钮
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function checkbox($publicSet, $privateSet, $value)
    {
        if(!is_array($value))
        {
            $value = explode('|', $value);
        }
        $i = 1;
        $data = '';
        $publicSet['name'] .= '[]';
        foreach($privateSet['_resource'] as $key => $val)
        {
            $publicSet['value'] = $key;
            // 备份原始 id
            $origin_id = $publicSet['id'];
            //if($i != 0)
            //{
                $publicSet['id'] .= '-' . $i;
            //}
            $attr = parent::_getAttr($publicSet);
            if(in_array($key, $value))
            {
                $isChecked = ' checked="checked" ';
            } else {
                $isChecked = '';
            }
            $data .= '<input type="checkbox" ' . $attr . $isChecked . '/><label for="' . $publicSet['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $publicSet['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 创建多选按钮
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function radio($publicSet, $privateSet, $value)
    {
        $i = 1;
        $data = '';
        foreach($privateSet['_resource'] as $key => $val)
        {
            $publicSet['value'] = $key;
            // 备份原始 id
            $origin_id = $publicSet['id'];
            //if($i != 0)
            //{
                $publicSet['id'] .= '-' . $i;
            //}
            $attr = parent::_getAttr($publicSet);
            $isChecked = $value == $key ? ' checked="checked" ' : '';
            $data .= '<input type="radio" ' . $attr . $isChecked . '/><label for="' . $publicSet['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $publicSet['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 创建列表
     *
     * @param array $publicSet 属性配置
     * @param array $privateSet 私有配置
     * @param array $value 初始值
     * @return string html
     * @todo 0 和 '' 的区分
     */
    public function select($publicSet, $privateSet, $value)
    {
        $attr = parent::_getAttr($publicSet);
        $data = '<select ' . $attr . '>';
        $isUsed = false;
        if(isset($privateSet['_resource']))
        {
            foreach($privateSet['_resource'] as $key => $val)
            {
                if(false == $isUsed && $value == $key)
                {
                    $isUsed = true;
                    $isSelected = ' selected="selected" ';
                } else {
                    $isSelected = '';
                }
                $data .= '<option value="' . $key . '"' . $isSelected . '>' . $val . '</option>';
            }
        }
        $data .= '</select>';
        return $data;
    }
}
