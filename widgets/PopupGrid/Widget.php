<?php
/**
 * Widget
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
 * @since       2010-10-04 18:54:37
 */

class PopupGrid_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'db'        => null,
        'field'     => 'id',
        'display'   => 'id',
        'layout'    => null,
        'criteria'  => array(),
        'order'     => array(),
    );
    
    /**
     * 生成弹出选择器
     * 
     * @param array $options 选项
     */
    public function render($options = null)
    {
        $options = (array)$options + $options['_form']['_relation'] + $this->_options;
        
        $lang = $this->_lang;
        $form = $this->_form;
        $jQuery = $this->_jQuery;
        
        $this->_minify->addArray(array(
            $jQuery->loadUi('position', false),
            $jQuery->loadUi('dialog', false),
            $jQuery->loadUi('mouse', false),
            $jQuery->loadUi('draggable', false),
            $jQuery->loadPlugin('tmpl', false),
            $this->_path . 'view/default.css',
            $this->_path . 'view/default.js',
        ));

        $id = $options['_form']['id'];

        $url = $this->_url->url($options['module'], 'index', array(
            'view' => 'content',
            'layout' => $options['layout'],
        ));

        // 设置新的表单属性
        $element = $options['_form'];
        $element['id'] = $element['id'] . '_value';
        $element['name'] = $element['name'] . '_value';
        $element['readonly'] = 'readonly';

        // 输入框显示的值
        if (isset($element['_value2'])) {
            $element['_value'] = $element['_value2'];
            $selected = $lang['LBL_SELECTED'];
        } else {
            $selected = $lang['LBL_NOT_SELECTED'];
        }
        $element['_value'] .= '(' . $selected . ')';

        require $this->_path . 'view/default.php';
    }
}
