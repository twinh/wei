<?php
/**
 * widget
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
 * @since       v0.7.0 2011-02-16 01:09:01
 * @todo        表单,前端验证(js),后端验证(php)如何解耦
 */

class Form_Widget extends Qwin_Widget_Abstract
{
    /**
     * 表单配置
     *
     * @var array
     */
    protected $_option = array(
        'id'        => 'form-%s',
        'meta'      => null,
        'action'    => 'add',
        'column'    => 2,
        'data'      => array(),
        'view'      => 'default.php',
        'validate'  => false,
    );

    /**
     * 自动加载配置
     * @var array
     */
    protected $_autoload = array(
        'lang' => true,
    );

    /**
     * 表单元素的配置,不带下划线的为Html属性,否则为私有属性
     * @var array
     */
    protected $_elementOption = array(
        '_type' => null,
        '_value' => null,
    );

    /**
     * 生成表单界面
     *
     * @param array $option 配置选项
     * @return Form_Widget 当前对象
     * @todo 表单地址,验证可选等
     */
    public function render($option)
    {
        // 合并选项
        $option = array_merge($this->_option, $option);
        $meta = $option['meta'];
        $data = $option['data'];
        $minify = $this->_widget->get('Minify');
        $lang = Qwin::call('-lang');
        $refererPage = urlencode(Qwin::call('-request')->server('HTTP_REFERER'));

        $option['id'] = sprintf($option['id'], $meta->getModule()->toId());

        // 表单布局
        $form = $this->getLayout($meta, 'edit', $meta['page']['tableLayout'], $data);
        $group = $meta['group'];

        // 验证代码
        if ($option['validate']) {
            $this->loadLanguage('validator');
            $validateCode = $this->getValidateCode($meta, $lang);
        }

        $file = $this->_rootPath . 'view/' . $option['view'];
        if (is_file($file)) {
            require $file;
        } else {
            require $this->_rootPath . 'view/default.php';
        }
        return $this;
    }

    /**
     * 生成表单元素的Html代码
     *
     * @return string
     */
    public function renderElement(array $option)
    {
        $option = array_merge($this->_elementOption, $option);

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
            $private['_resource'] = Qwin::callByArray($private['_resourceGetter']);
        }

        // 根据类型,生成代码
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

    public function renderElementExtend()
    {
        
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

    /**
     * 获取表格布局
     *
     * @param Qwin_Metadata $meta 元数据
     * @param string $action 小写的操作名称
     * @param int $column 每行几栏,只能是1或2
     */
    public function getLayout(Qwin_Metadata_Abstract $metaCopy, $action = 'add', $column = 2, array $dataCopy = array())
    {
        $orderedField = $this->getOrderedField($metaCopy);

        $layout = array();

        // 隐藏域的表单配置
        $hidden = array();

        // 布局上一个布局类型
        $prev = array();

        foreach ($orderedField as $field) {
            // 初始化元数据,表单,数据
            if (null == $field[1]) {
                $meta = $metaCopy;
                $data = $dataCopy;
                $form = $meta['field'][$field[0]]['form'];
            } else {
                $meta = $meta->getModelMetadataByAlias($meta, $field[1]);
                $data = $dataCopy[$field[1]];
                $form = $meta['field'][$field[0]]['form'];
                $form['id'] = $field[1] . '_' . $form['name'];
                $form['name'] = $field[1] . '[' . $form['name'] . ']';
            }
            //if (!isset($form['_value'])) {
                $form['_value'] = isset($data[$form['name']]) ? $data[$form['name']] : null;
            //}
            
            // 将隐藏域加入第一个布局中
            if ('hidden' == $form['_type']) {
                $hidden[] = $form;
                continue;
            }

            // 通过回调方法自定义处理
            $method = 'callback' . $action . 'Layout';
            if(method_exists($this, $method)) {
                $result = call_user_func_array(array($this, $method), array($meta['field'][$field[0]]));
                if (true === $result) {
                    $hidden[] = $form;
                    continue;
                }
            }

            $cell = array($meta['field'][$field[0]]['basic']['title'], $form);
            $group = $meta['field'][$field[0]]['basic']['group'];
            if (!isset($layout[$group])) {
                $layout[$group] = array();
                $prev[$group] = 2;
            }

            if (isset($meta['field'][$field[0]]['basic']['layout'])) {
                $type = $meta['field'][$field[0]]['basic']['layout'];
            } else {
                $type = $column;
            }

            // 占一格,自动向左填补
            if (1 === $type) {
                // 上一行为2,3,4
                if (1 != $prev[$group]) {
                    $layout[$group][] = array(
                        $cell,
                    );
                } else {
                    if (2 === count(end($layout[$group]))) {
                        $layout[$group][] = array(
                            $cell,
                        );
                    } else {
                        $layout[$group][key($layout[$group])][] = $cell;
                    }
                }
                $prev[$group] = 1;
                continue;
            }

            // 补齐上一行末尾
            if (1 === $prev[$group] && 1 === count(end($layout[$group]))) {
                $key = key($layout[$group]);
                $layout[$group][$key][] = '';
            }

            // 根据不同类型设置布局
            switch ($type) {
                // 独自占满一行
                case 2:
                    $content = array(
                        $cell,
                    );
                    break;

                // 占一格,放在左边,右边为空
                case 3:
                    $content = array(
                        $cell, '',
                    );
                    break;

                // 占一格,放在右边,左边为空
                case 4:
                    $content = array(
                        '', $cell,
                    );
                    break;

                // 未知布局,直接忽略
                default:
                    continue;
                    break;
            }
            $layout[$group][] = $content;
            $prev[$group] = $type;
        }

        // 修复最后一行末尾
        foreach ($layout as $group => &$row) {
            if (1 === $prev[$group] && 1 === count(end($row))) {
                $key = key($row);
                $row[$key][] = '';
            }
        }

        return array(
            'hidden' => $hidden,
            'element' => $layout,
        );
    }

    /**
     * 排列元数据
     *
     * @param Qwin_Application_Metadata $meta 元数据
     * @param array $orderedField 经过排列的域
     * @param string|false $relatedName 元数据关联模型的元数据名称,如果是主元数据,则为false
     * @return array 以顺序为键名,以域的名称为值的数组
     */
    public function getOrderedField(Qwin_Metadata_Abstract $meta, array $orderedField = null, $relatedName = null)
    {
        foreach ($meta['field'] as $name => $field) {
            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($orderedField[$order])) {
                $order++;
            }

            $orderedField[$order] = array(
                $field['form']['name'], $relatedName,
            );
        }

        // 获取关联元数据
        foreach ($meta->getModelMetadataByType('db') as $key => $relatedMeta) {
            if ('db' == $meta['model'][$key]['type']) {
                $relatedMeta = $this->getOrderedField($orderedField, $key);
            }
        }

        // 根据键名排序
        if (!$relatedName) {
            ksort($orderedField);
        }

        return $orderedField;
    }

    public function callbackAddLayout($field)
    {
        if('custom' == $field['form']['_type']) {
            return true;
        }
        return false;
    }

    public function callbackEditLayout($field)
    {
        if (1 == $field['attr']['isReadonly'] || 'custom' == $field['form']['_type']) {
            return true;
            // TODO
            //$meta['field']->set($name . '.form._type', 'hidden');
        }
        return false;
    }

    public function callbackViewLayout($field)
    {
        if (0 == $field['attr']['isView']) {
            return true;
        }
        return false;
    }

    /**
     * 将域的元数据转换成jQuery Validate 的验证配置数组
     *
     * @param array $fieldMeta 域的元数据配置
     * @return array jQuery Validate 的验证配置数组
     * @todo 语言为可选
     */
    public function getValidateCode(Qwin_Application_Metadata $meta, Qwin_Application_Language $lang = null, $relatedName = false)
    {
        !$lang && $lang = Qwin::call('-lang');
        $validation = array(
            'rules' => array(),
            'messages' => array(),
        );
        foreach ($meta['field'] as $name => $field) {
            if (empty($field['validator']['rule'])) {
                continue;
            }
            foreach($field['validator']['rule'] as $rule => $param) {
                if ($relatedName) {
                    $name = $relatedName . '[' . $name . ']';
                }
                $validation['rules'][$name][$rule] = $param;
                $validation['messages'][$name][$rule] = $this->format($lang->t($field['validator']['message'][$rule]), $param);
            }
        }

        // 关联元数据
        /*foreach($meta['model'] as $model)
        {
            if('db' != $model['type'])
            {
                continue;
            }
            $relatedMeta = $this->_manager->get($model['metadata']);
            $tempValidation = $this->getJQueryValidateCode($relatedMeta, $model['alias']);
            $validation['rules'] += $tempValidation['rules'];
            $validation['messages'] += $tempValidation['messages'];
        }*/

        if (false === $relatedName) {
            $validation = Qwin_Util_Array::jsonEncode($validation);
        }

        return $validation;
    }


    public function makeRequiredAtFront($rule)
    {
        // 将必填项放在数组第一位
        if (array_key_exists('required', $rule)) {
            $tmpArr = array(
                'required' => true,
            );
            unset($rule['required']);
            $rule = $tmpArr + $rule;
        }
        return $rule;
    }

    /**
     * 模拟jquery.format转换数据,将{i}替换为$replace[i]的值
     *
     * @param string $data 代转换的数据
     * @param array $repalce 转换的数组
     * @return string 转换后的数据
     */
    public function format($data, $repalce)
    {
        $repalce = (array)$repalce;
        $pos = strpos($data, '{0}');
        if (false !== $pos) {
            $search = array();
            $count = count($repalce);
            for ($i = 0;$i < $count; $i++) {
                $search[$i] = '{' . $i . '}';
            }
            $data = str_replace($search, $repalce, $data);
        }
        return $data;
    }
}