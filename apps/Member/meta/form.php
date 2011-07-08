<?php
/**
 * form
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
 * @since       2011-04-27 15:21:09
 */

return array(
    'fields' => array(
        'id' => array(
            '_type' => 'hidden',
        ),
        'group_id' => array(
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
            '_onEdit' => array(
                'readonly' => 'true',
            ),
            '_validator' => array(
                'rule' => array(
                    'required' => true,
                    'maxlength' => 40,
                ),
            ),
        ),
        'password' => array(
            '_type' => 'password',
            '_onAdd' => array(
                '_value' => '',
            ),
            '_onEdit' => array(
                '_type' => 'text',
                'readonly' => 'true',
            ),
            // TODO 更好的方法,如显示<em>(不可见)</em>
            '_onView' => array(
                '_value' => '●●●●●',
            ),
            '_sanitiser' => array(
                'db' => array(
                    'md5'
                ),
            ),
        ),
        'sex' => array(
            '_type' => 'select',
            '_resourceGetter' => array(
                array('Ide_Option_Widget', 'get'),
                'sex',
            ),
            '_sanitiser' => array(
                'list' => array(
                    array('Ide_Option_Widget', 'sanitise'),
                    'sex',
                ),
                'view' => array(
                    array('Ide_Option_Widget', 'sanitise'),
                    'sex',
                ),
            ),
        ),
        'birthday' => array(
            '_widgets' => 'datepicker',
        ),
        'language' => array(
            '_type' => 'select',
            '_resourceGetter' => array(
                array('Ide_Option_Widget', 'get'),
                'language',
            ),
        ),
        'email' => array(

        ),
        'first_name' => array(
            
        ),
        'last_name' => array(
            
        ),
        'photo' => array(
            
        ),
        'reg_ip' => array(
            
        ),
        'theme' => array(
            
        ),
        'telephone' => array(
            
        ),
        'mobile' => array(
            
        ),
        'homepage' => array(
            
        ),
        'address' => array(
            
        ),
    ),
    'columns' => 1,
    'topButtons' => true,
//    'buttons' => array(),
//    'files' => array(),
    'hidden' => array(
        'id',
    ),
    'fieldsets' => array(
        'basic' => array(
            
        ),
    ),
    'layout' => array(
        'GRP_BASIC' => array(
            array('group_id'),
            array('username'),
            array('password'),
            array('email'),
            array('first_name'),
            array('last_name'),
            array('sex'),
            array('photo'),
            array('birthday'),
            array('reg_ip'),
        ),
        'GRP_CUSTOMER' => array(
            array('language'),
            array('theme'),
        ),
        'GRP_MORE' => array(
            array('telephone'),
            array('mobile'),
            array('homepage'),
            array('address'),
        ),
    ),
);