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
    protected $_defaults = array(
        'id'        => 'qw-form-%s',
        'meta'      => null,
        'form'      => 'form',
        'db'        => 'db',
        'action'    => 'add',
        'column'    => 2,
        'data'      => array(),
        'view'      => 'default.php',
        'widget'    => true,
        'validate'  => true,
        'addSelect' => true,
        'select'    => array(
            'value' => 'NULL',
            'name'  => 'LBL_PLEASE_SELECT',
            'color' => null,
            'style' => null,
        ),
    );
    
    /**
     * 表单元素类型
     * @var array
     */
    protected $_elementTypes = array(
        'text'      => true,
        'textarea'  => true,
        'hidden'    => true,
        'select'    => true,
        'password'  => true,
        'checkbox'  => true,
        'radio'     => true,
        'file'      => true,
        'plain'     => true,
    );
    
    /**
     * 操作名称数组,不同操作下,根据表单的配置,显示不同的数据
     * 
     * @var array
     */
    protected $_actions = array(
        'add', 'edit', 'view',
    );

    /**
     * 表单元素的配置,不带下划线的为Html属性,否则为私有属性
     * @var array
     */
    protected $_elementoptions = array(
        'name' => null,
        'id' => null,
        '_type' => null,
        '_value' => null,
    );
    
    /**
     * 最近一次执行render保存的表单元数据
     * @var Qwin_Meta_Form
     */
    protected $_form;
    
    /**
     * 生成属性字符串
     *
     * @param array $options 属性数组,键名表示属性名称,值表示属性值
     * @return string 属性字符串
     */
    public function renderAttr($options)
    {
        $attr = '';
        foreach ($options as $name => $value) {
            if (!isset($name[0]) || '_' !== $name[0]) {
                $attr .= $name . '="' . htmlspecialchars((string)$value) . '" ';
            }
        }
        return $attr;
    }
    
    /**
     * 生成表单界面
     *
     * @param array $options 配置选项
     * @return Form_Widget 当前对象
     * @todo 表单地址,验证可选等
     */
    public function render($options = null)
    {
        // 合并选项
        $options = $this->_options = (array)$options + $this->_options;
        
        // 检查元数据是否合法
        /* @var $meta Meta_Widget */
        $meta = $options['meta'];
        if (!Qwin_Meta::isValid($meta)) {
            throw new Qwin_Widget_Exception('ERR_META_ILLEGAL');
        }

        // 检查列表元数据是否合法
        /* @var $form Qwin_Meta_Form */
        if (!($form = $meta->offsetLoad($options['form'], 'form'))) {
            throw new Qwin_Widget_Exception('ERR_FROM_META_NOT_FOUND');
        }

        foreach ($form['fields'] as $name => &$field) {
            // 为表单赋值
            if (array_key_exists($name, $options['data'])) {
                $field['_value'] = $options['data'][$name];
            }
            
            // TODO 源元数据不应该做如此更改
            // TODO 1.复制 2.开关
            if ('view' == $options['action']) {
                $field['_type'] = 'plain';
            // 将关联转换为资源
            } else {
                if (isset($field['_relation']) && !$field['_relation']['loaded']) {
                    $field['_resource'] = $this->relationToResource($field['_relation']);
                    $field['_relation']['loaded'] = true;
                }
            }
            
            // 根据操作动态调整表单元数据
            $action = '_on' . ucfirst($options['action']);
            if (isset($field[$action])) {
                $field = $field[$action] + $field;
            }
        }
        unset($field);
        
        // 删除多余或补全缺少的数据
        foreach ($form['layout'] as &$fieldset) {
            foreach ($fieldset as &$fields) {
                if (($count = count($fields)) == $form['columns']) {
                    continue;
                }
                if ($count > $form['columns']) {
                    $fields = array_slice($fields, 0, $form['columns']);
                } else  {
                    $fields = array_pad($fields, $form['columns'], '');
                }
            }
        }
        unset($fieldset, $fields);

        // 默认表单配置
        $lang = $this->_lang;
        $percent = $this->getColumnPercent($form['columns'] * 2);
        $refererPage = urlencode(Qwin::call('-request')->server('HTTP_REFERER'));
        $options['id'] = sprintf($options['id'], $meta['module']->getId());

        // 验证代码
        if ($options['validate']) {
            $this->_lang->appendByWidget('Validator');
            $validateCode = array(); //$this->getValidateCode($meta, $lang);
        }
        
        // 加载视图文件
        $file = $this->_path . 'view/' . $options['view'];
        if (!is_file($file)) {
            throw new Qwin_Widget_Exception('File "' . $file . '" not found.');
        }
        require $file;
        
        return $this;
    }
    
    /**
     * 获取表格栏目的宽度百分比
     * 
     * @param array $num 栏目数
     * @return array
     */
    public function getColumnPercent($num)
    {
        if (2 == $num) {
            return array(13.5, 87.5);
        }
        $source = array();
        $source[0] = (float)(100 / ($num * 2));
        $source[1] = $source[0] * 3;
        for ($i = 0; $i < $num; $i++) {
            $percent[] = $source[$i % 2];
        }
        return $percent;
    }
    
    /**
     * 将关联转换为资源
     * 
     * @param array $relation 关联数组
     * @return array
     */
    public function relationToResource($relation)
    {
        $dbData = Query_Widget::getByModule($relation['module'], $relation['db'])
            ->select($relation['field'] . ', ' . $relation['display'])
            ->execute();
        $resource = array();
        foreach ($dbData as $data) {
            $resource[$data[$relation['field']]] = array(
                'name' => $data[$relation['display']],
                'value' => $data[$relation['field']],
            );
        }
        return $resource;
    }

    /**
     * 生成表单元素的Html代码
     *
     * @param array $options 表单配置
     * @param mixed $value 表单的值，可选
     * @return string
     */
    public function renderElement(array $options)
    {
        $options = $options + $this->_elementoptions;
        
        // 检查表单元素类型是否合法
        $options['_type'] = strtolower($options['_type']);
        if (!isset($this->_elementTypes[$options['_type']])) {
            throw new Qwin_Widget_Exception('Form element type "' . $options['_type'] . '" invalid.');
        }
        
        // 假如设定了第二个参数，将作为表单的值
        $params = func_get_args();
        if (array_key_exists(1, $params)) {
            $options['_value'] = $params[1];
        }
        
        // 处理关联关系的资源
        if (isset($options['_relation']) && !$options['_relation']['loaded']) {
            $options['_resource'] = $this->relationToResource($options['_relation']);
        // 处理回调方法的资源
        } elseif (isset($options['_resourceGetter'])) {          
            $options['_resource'] = Qwin::call('-flow')->callOne($options['_resourceGetter']);
        }
        
        return call_user_func_array(
            array($this, 'render' . $options['_type']),
            array($options)
        );
    }

    /**
     * 生成表单元素微件
     *
     * @param array $options 表单配置
     * @return string
     * @todo 微件动态更改表单元素
     */
    public function renderWidget(array $options)
    {
        if (!$this->_options['widget'] || !isset($options['_widgets'])) {
            return false;
        }
        $widgets = &$options['_widgets'];
        !is_array($widgets) && $widgets = (array)$widgets;
        
        /* @var $flow Qwin_Flow */
        $flow = Qwin::call('-flow');
        $result = '';
        
        foreach ($widgets as $widget) {
            if (is_string($widget)) {
                $class = $widget . '_Widget';
                if (!class_exists($class)) {
                    if (!function_exists($widget)) {
                        throw new Qwin_Widget_Exception('Function "' . $widget . '" not found');
                    // 处理函数
                    } else {
                        $widget = array(
                            $widget,
                        );
                    }
                // 处理微件
                } else {
                    $widget = array(
                        array($class, 'render'),
                    );
                }
            }
            
            // 构造参数
            !isset($widget[1]) && $widget[1] = array();
            !isset($widget[1][0]) && $widget[1][0] = array();

            $widget[1][0] += array(
                '_form' => $options,
            );
            $result .= $flow->callOne($widget);
        }
        return $result;
    }

    /**
     * 生成纯文本域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderPlain($options)
    {
        return $options['_value'];
    }

    /**
     * 生成输入域,一般有text,hidden,file等
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderInput($options)
    {
        $options['type'] = $options['_type'];
        $options['value'] = $options['_value'];
        return '<input ' . $this->renderAttr($options) . '/>';
    }

    /**
     * 生成文本域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderText($options)
    {
        return $this->renderInput($options);
    }

    /**
     * 生成文件域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderFile($options)
    {
        return $this->renderInput($options);
    }

    /**
     * 生成密码域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderPassword($options)
    {
        return $this->renderInput($options);
    }

    /**
     * 生成隐藏域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderHidden($options)
    {
        return $this->renderInput($options);
    }

    /**
     * 生成多行文本域
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderTextarea($options)
    {
        return '<textarea ' . $this->renderAttr($options) . '>' . $options['_value'] . '</textarea>';
    }

    /**
     * 生成多选按钮
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderCheckbox($options)
    {
        $value = is_array($options['_value']) ? $options['_value'] : explode('|', $options['_value']);
        $options['name'] .= '[]';
        $i = 0;
        $data = '';
        foreach ($options['_resource'] as $opts) {
            // 转换资源
            $resource = $this->filterResource($options['_resource']);

            $attr = $options;
            $attr['id'] .= '-' . $i;
            $htmlAttr = $this->renderAttr($attr);
            $isChecked = in_array($opts['value'], $value) ? ' checked="checked" ' : '';
            $data .= '<input type="checkbox" ' . $htmlAttr . $isChecked . '/><label for="' . $attr['id'] . '"' . $this->filterOptionStyle($opts) . '>' . $opts['name'] . '</label>';
            $i++;
        }
        return $data;
    }

    /**
     * 生成单选按钮
     * 
     * @param array $options 选项
     * @return string 
     */
    public function renderRadio($options)
    {
        $value = $options['_value'];
        $i = 0;
        $data = '';
        foreach ($options['_resource'] as $opts) {
            // 转换资源
            $resource = $this->filterResource($options['_resource']);
            
            $attr = $options;
            $attr['id'] .= '-' . $i;
            $htmlAttr = $this->renderAttr($attr);
            $isChecked = $value == $opts['value'] ? ' checked="checked" ' : '';
            $data .= '<input type="radio" ' . $htmlAttr . $isChecked . '/><label for="' . $attr['id'] . '"' . $this->filterOptionStyle($opts) . '>' . $opts['name'] . '</label>';
            $i++;
        }
        return $data;
    }

    /**
     * 生成选择列表
     * 
     * @param array $options 选项
     * @return string
     * @todo 0 和 '' 的区分等 
     */
    public function renderSelect($options)
    {
        $value = $options['_value'];
        $attr = $this->renderAttr($options);
        $data = '<select ' . $attr . '>';
        $isUsed = false;
        if (isset($options['_resource'])) {
            // 转换资源
            $resource = $this->filterResource($options['_resource']);
            if ($this->_options['addSelect']) {
                $this->_options['select']['name'] = $this->_lang[$this->_options['select']['name']];
                $resource = array(
                    $this->_options['select']
                ) + $resource;
            }
            foreach($resource as $opts) {
                if(false == $isUsed && $value == $opts['value']) {
                    $isUsed = true;
                    $isSelected = ' selected="selected" ';
                } else {
                    $isSelected = '';
                }
                $data .= '<option' . $this->filterOptionStyle($opts) . ' value="' . $opts['value'] . '"' . $isSelected . '>' . $opts['name'] . '</option>';
            }
        }
        $data .= '</select>';
        return $data;
    }

    /**
     * 转换资源为选项模块的形式
     *
     * @param array $resource
     * @param array $options 配置选项
     * @return array
     * @todo 耦合?
     */
    public function filterResource($resource, $options = null)
    {
        if (empty($resource)) {
            return $resource;
        }
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
     * 附加颜色到样式中
     *
     * @param array $options 选项数据
     * @return string
     */
    public function filterOptionStyle($options)
    {
        isset($options['color']) && $options['style'] = 'color:' . $options['color'] . ';' . $options['style'];
        isset($options['style']) && $options['style'] = ' style="' . $options['style'] . '"';
        return isset($options['style']) ? $options['style'] : null;
    }

    /**
     * 将域的元数据转换成jQuery Validate 的验证配置数组
     *
     * @param array $fieldMeta 域的元数据配置
     * @return array jQuery Validate 的验证配置数组
     * @todo 语言为可选
     */
    public function getValidateCode(Qwin_Application_Meta $meta, Qwin_Application_Language $lang = null, $relatedName = false)
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
            $relatedMeta = $this->_manager->get($model['meta']);
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
