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
            'els' => array(
                array(
                    'name' => 'id',
                    'readonly' => true,
                    'label' => '编号',
                    'type' => 'hidden',
                ),
                array(
                    'els' => array(
                        array(
                            'label' => 'test',
                            'type' => 'text',
                        ),
                        array(
                            'label' => 'test2',
                            'type' => 'text',
                        ),
                        array(
                            'label' => 'test2',
                            'type' => 'text',
                        ),
                    ),
                ),
                array(
                    'els' => array(
                        array(
                            'label' => 'test3',
                            'type' => 'text',
                        ),
                        array(
                            'readonly' => true,
                            '_label' => '分组',
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
                    ),
                ),
                array(
                    '_label' => '用户名',
                    'name' => 'username',
                    '_type' => 'text',
                    '_onEdit' => array(
                        'readonly' => 'true',
                    ),
                ),
                array(
                    '_label' => '密码',
                    '_type' => 'password',
                    '_onAdd' => array(
                        '_value' => '',
                    ),
                    '_onEdit' => array(
                        '_type' => 'text',
                        'readonly' => 'true',
                        '_value' => '●●●●●'
                    ),
                    '_onView' => array(
                        '_value' => '●●●●●',
                    ),
                ),
                array(
                    '_label' => '日期',
                    '_type' => 'text',
                    '_widgets' => 'datepicker',
                ),
            ),
            '_buttons' => array(
                array(
                    'type' => 'submit',
                    '_label' => 'Submit',
                    '_icon' => 'ui-icon-check',
                ),
                array(
                    'type' => 'reset',
                    '_label' => 'Cancel',
                    '_icon' => 'ui-icon-arrowreturnthick-1-w',
                ),
            ),
        );
    }
    
    public function call($name = null, $module = null)
    {
        return $this;
    }
}