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
    'group_id' => array(
        '_type' => 'select',
        '_resourceGetter' => array(
            array('Com_Category_Widget', 'get'),
            array(
                'com/member/group',
                null,
                array('id', null, 'name'),
            ),
        ),
        'sanitiser' => array(
            array('Com_Category_Widget', 'sanitise'),
            array(
                'com/member/group',
                null,
                array('id', null, 'name'),
            ),
        ),
        'validator' => array(
            'rule' => array(
                'notNull' => true,
            ),
        ),
    ),
    'username' => array(
        'validator' => array(
            'rule' => array(
                'required' => true,
                'maxlength' => 40,
            ),
        ),
    ),
    'password' => array(
        'form' => array(
            '_type' => 'password',
        ),
        'attr' => array(
            'isReadonly' => 1,
            'isView' => 0,
        ),
        'sanitiser' => array(
            'db' => array(
                'md5'
            )
        ),
        'validator' => array(
            'rule' => array(
                'required' => true,
                'rangelength' => array(5, 40),
            ),
        ),
    ),
    'password2' => array(
        'form' => array(
            '_type' => 'password',
        ),
        'attr' => array(
            'isReadonly' => 1,
            'isView' => 0,
            'isDbField' => 0,
        ),
        'validator' => array(
            'rule' => array(
                'required' => true,
                'equalTo' => '#password',
            ),
        ),
    ),
    'email' => array(
        'attr' => array(
            'isList' => 1,
        ),
        'validator' => array(
            'rule' => array(
                'required' => true,
                'email' => true,
                'maxlength' => 256,
            ),
        ),
    ),
    'first_name' => array(
    ),
    'last_name' => array(
    ),
    'photo' => array(
        'form' => array(
            '_widget' => array(
                'fileTree',
                'ajaxUpload',
            ),
        ),
    ),
    'sex' => array(
        'form' => array(
            '_type' => 'select',
            '_resourceGetter' => array(
                array('Ide_Option_Widget', 'get'),
                'sex',
            ),
        ),
        'sanitiser' => array(
            'list' => array(
                array('Ide_Option_Widget', 'sanitise'),
                'sex',
            ),
            'view' => 'list',
        ),
        'attr' => array(
            'isLink' => 1,
        ),
    ),
    'birthday' => array(
        'form' => array(
            '_widget' => array(
                array(
                    array('Datepicker_Widget', 'render')
                ),
            ),
        )
    ),
    'reg_ip' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    'theme' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    'language' => array(
        'basic' => array(
            'group' => 1,
        ),
        'form' => array(
            '_type' => 'select',
            '_resourceGetter' => array(
                array('Ide_Option_Widget', 'get'),
                'language',
            ),
        ),
    ),
    'telephone' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    'mobile' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    'homepage' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    'address' => array(
        'basic' => array(
            'group' => 1,
        ),
    ),
    '_layout' => array(
        array('field1', 'field2'),
        array('field3', 'field4'),
        array('field5', 'field6'),
    ),
);