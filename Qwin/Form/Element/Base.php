<?php
/**
 * form 的名称
 *
 * form 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2009-11-20 15:36:01 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 * @todo      分离等
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
        $i = 0;
        $data = '';
        $pub_set['name'] .= '[]';
        foreach($pri_set['_resource'] as $key => $val)
        {
            $pub_set['value'] = $key;
            // 备份原始 id
            $origin_id = $pub_set['id'];
            if($i != 0)
            {
                $pub_set['id'] .= '-' . $i;
            }
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
        $i = 0;
        $data = '';
        foreach($pri_set['_resource'] as $key => $val)
        {
            $pub_set['value'] = $key;
            // 备份原始 id
            $origin_id = $pub_set['id'];
            if($i != 0)
            {
                $pub_set['id'] .= '_' . $i;
            }
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