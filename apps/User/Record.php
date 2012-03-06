<?php
/**
 * Record
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
 * @since       2011-04-27 14:57:25
 */
class User_Record extends Qwin_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');

        $this->hasColumn('id', 'string', 36, array(
            'type' => 'string',
            'length' => 36,
            'fixed' => true,
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => false,
        ));

        $this->hasColumn('group_id');

        $this->hasColumn('username');

        $this->hasColumn('password');

        $this->hasColumn('email');

        $this->hasColumn('first_name');

        $this->hasColumn('last_name');

        $this->hasColumn('photo');

        $this->hasColumn('sex');

        $this->hasColumn('birthday');

        $this->hasColumn('reg_ip');

        $this->hasColumn('theme');

        $this->hasColumn('language');

        $this->hasColumn('telephone');

        $this->hasColumn('mobile');

        $this->hasColumn('homepage');

        $this->hasColumn('address');

        $this->hasColumn('created_by');

        $this->hasColumn('date_created');

        $this->hasColumn('modified_by');

        $this->hasColumn('date_modified');
    }

    public function setUp()
    {
        $this->hasOne('Group_Record as group', array(
                'local' => 'group_id',
                'foreign' => 'id'
            )
        );
    }

    public function preInsert($event)
    {
        $this->id = Qwin::getInstance()->uuid();

        $this->date_created = date('Y-m-d H:i:s', time());

        $this->date_modified = $this->date_created;

        $this->created_by = Qwin::getInstance()->user()->offsetGet('id');

        $this->modified_by = $this->created_by;
    }

    public function preUpdate($event)
    {
        $this->date_modified = date('Y-m-d H:i:s', time());

        $this->modified_by = Qwin::getInstance()->user()->offsetGet('id');
    }
}
