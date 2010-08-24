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
    /**
     * 创建文本域
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function text($pub_set, $pri_set, $value)
    {
        $set_addition = array(
            'type' => 'text',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $pub_set);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建密码域
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function password($pub_set, $pri_set, $value)
    {
        $set_addition = array(
            'type' => 'password',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $pub_set);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建隐藏域
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function hidden($pub_set, $pri_set, $value)
    {
        $set_addition = array(
            'type' => 'hidden',
            'value' => $value,
        );
        $attr = parent::_getAttr($set_addition + $pub_set);
        $data = '<input ' . $attr . '/>';
        return $data;
    }

    /**
     * 创建文本区域
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function textarea($pub_set, $pri_set, $value)
    {
        $attr = parent::_getAttr($pub_set);
        $data = '<textarea ' . $attr . '>' . $value . '</textarea>';
        return $data;
    }

    /**
     * 创建多选按钮
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function checkbox($pub_set, $pri_set, $value)
    {
        if(!is_array($value))
        {
            $value = explode('|', $value);
        }
        $i = 1;
        $data = '';
        $pub_set['name'] .= '[]';
        foreach($pri_set['_resource'] as $key => $val)
        {
            $pub_set['value'] = $key;
            // 备份原始 id
            $origin_id = $pub_set['id'];
            //if($i != 0)
            //{
                $pub_set['id'] .= '-' . $i;
            //}
            $attr = parent::_getAttr($pub_set);
            if(in_array($key, $value))
            {
                $is_checked = ' checked="checked" ';
            } else {
                $is_checked = '';
            }
            $data .= '<input type="checkbox" ' . $attr . $is_checked . '/><label for="' . $pub_set['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $pub_set['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 创建多选按钮
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     */
    public function radio($pub_set, $pri_set, $value)
    {
        $i = 1;
        $data = '';
        foreach($pri_set['_resource'] as $key => $val)
        {
            $pub_set['value'] = $key;
            // 备份原始 id
            $origin_id = $pub_set['id'];
            //if($i != 0)
            //{
                $pub_set['id'] .= '-' . $i;
            //}
            $attr = parent::_getAttr($pub_set);
            $is_checked = $value == $key ? ' checked="checked" ' : '';
            $data .= '<input type="radio" ' . $attr . $is_checked . '/><label for="' . $pub_set['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $pub_set['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 创建列表
     *
     * @param array $pub_set 属性配置
     * @param array $pri_set 私有配置
     * @param array $value 初始值
     * @return string html
     * @todo 0 和 '' 的区分
     */
    public function select($pub_set, $pri_set, $value)
    {
        $attr = parent::_getAttr($pub_set);
        $data = '<select ' . $attr . '>';
        if(isset($pri_set['_resource']))
        {
            foreach($pri_set['_resource'] as $key => $val)
            {
                $is_selected = $value == $key ? ' selected="selected" ' : '';
                $data .= '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
            }
        }
        $data .= '</select>';
        return $data;
    }
}
