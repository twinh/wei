<?php
/**
 * Form
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-27 22:57:22
 */

class Qwin_Widget_Form extends Qwin_Widget_Abstract
{
    protected $_option = array(
        '_type' => null,
        '_value' => null,
    );

    /**
     * 生成基本的表单元素
     *
     * @param array $option
     * @param Qwin_Application_View $view
     */
    public function render($option, $view = null)
    {
        $option = array_merge($this->_option, $option);

        $public = array();
        $private = array();

        // 分出公有和私有属性
        foreach ($option as $name => $value) {
            if (isset($name[0]) && '_' == $name[0]) {
                $private[$name] = $value;
            } else {
                $public[$name] = $value;
            }
        }

        //$set['id'] = preg_replace("/(\w*)\[(\w+)\]/", "$1-$2", $set['name']);

        // 转换资源 
        if (isset($private['_resourceGetter'])) {
            $private['_resource'] = Qwin_Class::callByArray($private['_resourceGetter']);
        }

        switch ($private['_type']) {
            case 'text' :
                return $this->renderText($public, $private);

            case 'textarea' :
                return $this->renderTextarea($public, $private);

            case 'hidden' :
                return $this->renderHidden($public, $private);

             case 'select' :
                return $this->renderSelect($public, $private);

            case 'password' :
                return $this->renderPassword($public, $private);

            case 'checkbox' :
                return $this->renderCheckbox($public, $private);

            case 'radio' :
                return $this->renderRadio($public, $private);

            case 'file' :
                return $this->renderFile($public, $private);
                
            default :
                return '';
        }
    }

    /**
     * 生成纯文本域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 文本数据
     */
    public function renderPlain($public, $private)
    {
        return $private['_value'];
    }

    /**
     * 生成输入域
     *
     * @param string $type 输入域的类型,一般有text,hidden,file等
     * @param array $public 公有的属性
     * @return string 表单数据
     */
    public function renderInput($type, array $public, array $private = null)
    {
        $public['type'] = $type;
        $public['value'] = $private['_value'];
        return '<input ' . $this->renderAttr($public) . '/>';
    }

    /**
     * 生成文本域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderText(array $public, array $private = null)
    {
        return $this->renderInput('text', $public, $private);
    }

    /**
     * 生成文件域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderFile(array $public, array $private = null)
    {
        return $this->renderInput('file', $public, $private);
    }

    /**
     * 生成密码域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderPassword(array $public, array $private = null)
    {
        return $this->renderInput('password', $public, $private);
    }

    /**
     * 生成隐藏域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderHidden(array $public, array $private = null)
    {
        return $this->renderInput('hidden', $public, $private);
    }

    /**
     * 生成多行文本域
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderTextarea(array $public, array $private = null)
    {
        return '<textarea ' . $this->renderAttr($public) . '>' . $private['_value'] . '</textarea>';
    }

    /**
     * 生成多选按钮
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderCheckbox(array $public, array $private = null)
    {
        $value = $private['_value'];
        if (!is_array($value)) {
            $value = explode('|', $value);
        }
        $i = 1;
        $data = '';
        $public['name'] .= '[]';
        foreach($private['_resource'] as $key => $val)
        {
            $public['value'] = $key;
            // 备份原始 id
            $origin_id = $public['id'];
            //if($i != 0)
            //{
                $public['id'] .= '-' . $i;
            //}
            $attr = $this->renderAttr($private);
            if(in_array($key, $value))
            {
                $isChecked = ' checked="checked" ';
            } else {
                $isChecked = '';
            }
            $data .= '<input type="checkbox" ' . $attr . $isChecked . '/><label for="' . $public['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $public['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 生成单选按钮
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     */
    public function renderRadio(array $public, array $private = null)
    {
        $value = $private['_value'];
        $i = 1;
        $data = '';
        foreach($private['_resource'] as $key => $val)
        {
            $public['value'] = $key;
            // 备份原始 id
            $origin_id = $public['id'];
            //if($i != 0)
            //{
                $public['id'] .= '-' . $i;
            //}
            $attr = $this->renderAttr($public);
            $isChecked = $value == $key ? ' checked="checked" ' : '';
            $data .= '<input type="radio" ' . $attr . $isChecked . '/><label for="' . $public['id'] . '">' . $val . '</label>';
            // 还原原始 id
            $public['id'] = $origin_id;
            $i++;
        }
        return $data;
    }

    /**
     * 生成选择列表
     *
     * @param array $public 公有的属性
     * @param array $private 私有的配置
     * @return string 表单数据
     * @todo 0 和 '' 的区分等
     */
    public function renderSelect($public, $private)
    {
        $value = $private['_value'];
        $attr = $this->renderAttr($public);
        $data = '<select ' . $attr . '>';
        $isUsed = false;
        if (isset($private['_resource'])) {
            // 转换资源
            $resource = $this->filterResource($private['_resource']);
            foreach($resource as $option) {
                // 附加颜色到样式中
                null != $option['color'] && $option['style'] = 'color:' . $option['color'] . ';' . $option['style'];
                null != $option['style'] && $option['style'] = ' style="' . $option['style'] . '"';
                if(false == $isUsed && $value == $option['value']) {
                    $isUsed = true;
                    $isSelected = ' selected="selected" ';
                } else {
                    $isSelected = '';
                }
                $data .= '<option' . $option['style'] . ' value="' . $option['value'] . '"' . $isSelected . '>' . $option['name'] . '</option>';
            }
        }
        $data .= '</select>';
        return $data;
    }

    /**
     * 转换资源为选项模块的形式
     *
     * @param array $resource
     * @param array $option 配置选项
     * @return array
     * @todo 耦合?
     */
    public function filterResource($resource, $option = null)
    {
        // 认定为选项模块的选项
        $element = $resource[key($resource)];
        if (is_array($element)) {
            return $resource;
        }

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

        return $return;
    }
}
