<?php
/**
 * Option
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
 * @since       2011-01-20 15:03:12
 */

class Qwin_Widget_Editor_Option
{
    public function render($meta)
    {
        $lang = Qwin::call('-lang');
        $jquery = Qwin::call('Qwin_Resource_JQuery');
        $id = $meta['id'];
        $view = Qwin::call('-view');
        $cssPacker = Qwin::call('Qwin_Packer_Css');
        $jsPacker = Qwin::call('Qwin_Packer_Js');
        $codeMeta = $view['metaHelper']->getMetadataByAsc(array(
            'namespace' => 'Common',
            'module' => 'Option',
            'controller' => 'Code',
        ));
        
        $jQueryFile = array(
            'core'  => $jquery->loadUi('core', false),
            'widget' => $jquery->loadUi('widget', false),
            'mouse' => $jquery->loadUi('mouse', false),
            'sortable' => $jquery->loadUi('sortable', false),
            'optionEditor' => $jquery->loadPlugin('optioneditor', null, false),
        );
        $cssPacker
            ->add($jQueryFile['mouse']['css'])
            ->add($jQueryFile['sortable']['css'])
            ->add($jQueryFile['optionEditor']['css']);
        $jsPacker
            ->add($jQueryFile['mouse']['js'])
            ->add($jQueryFile['sortable']['js'])
            ->add(QWIN . '/js/qwin/json2.js')
            ->add($jQueryFile['optionEditor']['js']);

        require QWIN . '/view/widget/editor-option.php';
    }
}
