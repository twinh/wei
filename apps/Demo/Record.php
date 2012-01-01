<?php
/**
 * DbRecord
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2011-09-19 14:46:01
 */

class Demo_Record extends Qwin_Record
{
    public function getRecordData()
    {
        return $this->options = array(
            'fields' => array(
                'id' => array(

                ),
                'title' => array(

                ),
                'code' => array(

                ),
                'tags' => array(

                ),
                'date_created' => array(

                ),
                'created_by' => array(

                ),
                'date_modified' => array(

                ),
                'modified_by' => array(

                ),
                'is_deleted' => array(

                ),
            ),
            'table' => 'demo',
            'relations' => array(

            ));/*array(
            'fields' => array(
                'id' => array(
                    'type' => 'string',
                    'length' => 36,
                    'fixed' => true,
                    'unsigned' => false,
                    'primary' => true,
                    'autoincrement' => false,
                ),
                'group_id' => array(
                    'type' => 'string',
                    'length' => 36,
                    'fixed' => true,
                    'unsigned' => false,
                    'primary' => false,
                    'notnull' => true,
                    'autoincrement' => false,
                ),
                'username' => array(
                    'readonly' => true,
                    'type' => 'string',
                    'length' => 32,
                    'fixed' => false,
                    'unsigned' => false,
                    'primary' => false,
                    'notnull' => true,
                    'autoincrement' => false,
                ),
                'password' => array(
                    'readonly' => true,
                    '_sanitiser' => array(
                        array(
                            'md5',
                        ),
                    ),
                    'type' => 'string',
                    'length' => 32,
                    'fixed' => false,
                    'unsigned' => false,
                    'primary' => false,
                    'notnull' => true,
                    'autoincrement' => false,
                ),
//                'password2' => array(
//                    'dbField' => 0,
//                    'dbQuery' => 0,
//                ),
                'email' => array(
                    'type' => 'string',
                    'length' => 256,
                    'fixed' => false,
                    'unsigned' => false,
                    'primary' => false,
                    'notnull' => true,
                    'autoincrement' => false,
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
            'id' => 'id',
            'table' => 'member',
            'mainField' => 'username',
            'relations' => array(
                'group' => array(
                    'module' => 'member/group',
                    'alias' => 'group',
                    'local' => 'group_id',
                ),
            ),
            'limit' => 10,
        );*/
    }
}
