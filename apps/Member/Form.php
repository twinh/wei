<?php

/**
 * Form
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
 * @since       2011-08-14 10:09:49
 */
class Member_Form extends Qwin_Form
{
    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->options = array(
            'style' => array(
                'padding' => '5px',
                'width' => '600px',
            ),
            'fieldDefaults' => array(
                'type' => 'text',
                'attr' => array(
                    
                ),
            ),
            'lableDefaults' => array(
                'width' => '75',
            ),
            'elems' => array(
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'label' => 'id',
                ),
                'group_id' => array(
                    'name' => 'group_id',
                    'label' => 'group_id',
                    'readonly' => true,
                    '_link' => true,
                    '_type' => 'text',
                    '_relation' => array(
                        'module' => 'member/group',
                        'alias' => 'group',
                        'display' => 'name',
                        'loaded' => true,
                    ),
                    '_widgets' => array(
                        array(
                            array('PopupPicker_Widget', 'render'),
                            array(array(
                                'layout' => 'id,name,date_modified',
                            )),
                        ),
                    ),
                ),
                'username' => array(
                    'name' => 'username',
                    'label' => 'username',
                    '_onEdit' => array(
                        'readonly' => 'true',
                    ),
                ),
                'password' => array(
                    'name' => 'password',
                    'label' => 'password',
                    '_type' => 'password',
                    '_onAdd' => array(
                        '_value' => '',
                    ),
                    '_onEdit' => array(
                        '_type' => 'text',
                        'readonly' => 'true',
                        '_value' => '●●●●●'
                    ),
                    // TODO 更好的方法,如显示<em>(不可见)</em>
                    '_onView' => array(
                        '_value' => '●●●●●',
                    ),
                ),
                'sex' => array(
                    'name' => 'sex',
                    'label' => 'sex',
                    '_type' => 'checkbox',
                    '_resourceGetter' => array(
                        array('Ide_Option_Widget', 'get'),
                        'sex',
                    ),
                    '_onView' => array(
                        '_sanitiser' => array(
                            array(
                                array('Ide_Option_Widget', 'sanitise'),
                                array('sex'),
                            ),
                        ),
                    ),
                ),
                'birthday' => array(
                    'name' => 'birthday',
                    'label' => 'birthday',
                    '_widgets' => 'datepicker',
                ),
                'language' => array(
                    'name' => 'language',
                    'label' => 'language',
                    '_type' => 'select',
                    '_resourceGetter' => array(
                        array('Ide_Option_Widget', 'get'),
                        'language',
                    ),
                ),
                'email' => array(
                    'name' => 'email',
                    'label' => 'email',
                ),
                'first_name' => array(
                    'name' => 'first_name',
                    'label' => 'first_name',
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'label' => 'last_name',
                ),
                'photo' => array(
                    'name' => 'photo',
                    'label' => 'photo',
                ),
                'reg_ip' => array(
                    'name' => 'reg_ip',
                    'label' => 'reg_ip',
                ),
                'theme' => array(
                    'name' => 'theme',
                    'label' => 'theme',
                    '_type' => 'select',
                    '_resourceGetter' => array(
                        array('Style_Widget', 'getResource'),
                    ),
                ),
                'date' => array(
                    'name' => 'date',
                    'label' => 'date',
                ),
                'time' => array(
                    'name' => 'time',
                    'label' => 'time',
                ),
                'telephone' => array(
                    'name' => 'telephone',
                    'label' => 'telephone',
                ),
                'mobile' => array(
                    'name' => 'mobile',
                    'label' => 'mobile',
                ),
                'homepage' => array(
                    'name' => 'homepage',
                    'label' => 'homepage',
                ),
                'address' => array(
                    'name' => 'address',
                    'label' => 'address',
                ),
            ),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'label' => 'Submit',
                    'icon' => 'ui-icon-check',
                ),
                array(
                    'type' => 'reset',
                    'label' => 'Cancel',
                    'icon' => 'ui-icon-arrowreturnthick-1-w',
                ),
            ),
        );
    }
    
    public function call($name = null, $module = null)
    {
        return $this;
    }
}