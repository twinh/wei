<?php
/**
 * db
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
 * @since       2011-04-27 14:57:25
 */

return array(
    'fields' => array(
        'group_id' => array(
            'validator' => array(
                'rule' => array(
                    'notNull' => true,
                ),
            ),
        ),
        'username' => array(
            'attr' => array(
                'isReadonly' => 1,
            ),
            'validator' => array(
                'rule' => array(
                    'required' => true,
                    'maxlength' => 40,
                ),
            ),
        ),
        'password' => array(
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
            'dbField' => 0,
            'dbQuery' => 0,
            'validator' => array(
                'rule' => array(
                    'required' => true,
                    'equalTo' => '#password',
                ),
            ),
        ),
        'email' => array(
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
        ),
        'sex' => array(

        ),
        'birthday' => array(
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
    ),
    'id' => 'id',
    'table' => 'member',
    'nameField' => array(
        'username',
    ),
);