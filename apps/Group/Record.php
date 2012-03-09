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
 * @since       2011-07-06 19:31:23
 */

class Group_Record extends Qwin_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user_group');

        $this->hasColumn('name', 'string', 255);

        $this->hasColumn('description');

        $this->hasColumn('created_by');

        $this->hasColumn('modified_by');

        $this->hasColumn('date_created');

        $this->hasColumn('date_modified');
    }

    public function setUp()
    {
        $this->actAs('NestedSet');

        $this->hasOne('User_Record as creator', array(
                'local' => 'created_by',
                'foreign' => 'id'
            )
        );

        $this->hasOne('User_Record as modifier', array(
                'local' => 'modified_by',
                'foreign' => 'id'
            )
        );
    }

    public function getParentOptions()
    {
        $tree = Doctrine_Core::getTable(__CLASS__)->getTree();
        foreach ($tree->fetchTree() as $node) {
            $options[$node['id']] = str_repeat('--', $node['level']) . $node['name'];
        }
        return $options;
    }

    public function preInsert($event)
    {
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