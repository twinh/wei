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
 * @since       2011-04-11 02:50:46
 */

class OptionEditor_Widget extends Qwin_Widget_Abstract
{
    public function render($options = null) {
        $lang = $this->_lang;
        $jQuery = $this->_widget->get('JQuery');
        $minify = $this->_widget->get('Minify');
        $form = $this->_widget->get('Form');

        $id = $options['form']['id'];
        $view = $this->_View;

        // todo 更合适的加载方式？
        $codeMeta = Qwin_Meta::getInstance()->get('Ide_Option_CodeMeta');

        $jQueryFile = array(
            'mouse' => $jQuery->loadUi('mouse', false),
            'sortable' => $jQuery->loadUi('sortable', false),
        );

        $minify
            ->add($jQueryFile['mouse']['css'])
            ->add($jQueryFile['sortable']['css'])
            ->add($jQueryFile['mouse']['js'])
            ->add($jQueryFile['sortable']['js'])
            ->add($this->_path . 'source/style.css')
            ->add($this->_path . 'source/js.js');

        require $this->_path . 'view/default.php';
    }
}
