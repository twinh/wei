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
     * 
     * @param array $options 选项
     */
    public function render($options)
    {
        $jQuery = $this->_widget->get('JQuery');
        $minify = $this->_widget->get('Minify');
        $form = $this->_widget->get('Form');

        $files = array(
            'position' => $jQuery->loadUi('position', false),
            'dialog' => $jQuery->loadUi('dialog', false),
        );
        
        $minify
            ->add($files['position']['css'])
            ->add($files['dialog']['css'])
            ->add($files['position']['js'])
            ->add($files['dialog']['js'])
            ->add($this->_rootPath . 'view/js.js');

        $lang = $this->_lang;
        $id = $options['form']['id'];

        $url = Qwin::call('-url')->url($options['module'], 'index', array(
            'popup' => 1,
            'ajax' => 1,
            'list' => $options['list'],
        ));
        $title = $lang->t('LBL_PLEASE_SELECT') . $lang->t($options['title']);

        // 设置新的表单属性
        $meta = $options['form'];
        foreach (array('name', 'id') as $value) {
            $meta[$value] .= '_value';
        }
        $meta['readonly'] = 'readonly';

        // 输入框显示的值
        if(isset($meta['_value2']))
        {
            $meta['_value'] = $meta['_value2'];
            $selected = $lang->t('LBL_SELECTED');
        } else {
            $selected = $lang->t('LBL_NOT_SELECTED');
        }
        $meta['_value'] .= '(' . $selected . ', ' . $lang->t('LBL_READONLY') . ')';

        require $this->_rootPath . 'view/default.php';
    }
}