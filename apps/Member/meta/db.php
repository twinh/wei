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
        'id' => array(
            
        ),
        'group_id' => array(
            
        ),
        'username' => array(
            'readonly' => true,
        ),
        'password' => array(
            'readonly' => true,
            '_sanitiser' => array(
                array(
                    'md5',
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
        ),
        'theme' => array(
        ),
        'language' => array(
        ),
        'telephone' => array(
        ),
        'mobile' => array(
        ),
        'homepage' => array(
        ),
        'address' => array(
        ),
        'created_by' => array(
            'readonly' => true,
        ),
        'date_created' => array(
            'readonly' => true,
        ),
        'modified_by' => array(
            
        ),
        'date_modified' => array(
            
        ),
    ),
    'uid' => 'db',
    'id' => 'id',
    'table' => 'member',
    'mainField' => 'username',
    'relations' => array(
        'group' => array(
            'module'    => 'member/group',
            'alias'     => 'group',
            'db'        => 'db',
            'relation'  => 'hasOne',
            'local'     => 'group_id',
            'foreign'   => 'id',
            'type'      => 'db',
            'fieldMap'  => array(), // ?是否仍需要
            'enabled'   => true,
        ),
    ),
);