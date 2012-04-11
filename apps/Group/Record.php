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
        $this->setTableName('group');

        $this->hasColumn('name', 'string', 255);

        $this->hasColumn('description');

        $this->hasColumn('created_by');

        $this->hasColumn('updated_by');

        $this->hasColumn('created_at');

        $this->hasColumn('updated_at');
    }

    public function setUp()
    {
        $this->actAs('NestedSet');

        $this->hasOne('User_Record as creator', array(
                'local' => 'created_by',
                'foreign' => 'id'
            )
        );

        $this->hasOne('User_Record as updater', array(
                'local' => 'updated_by',
                'foreign' => 'id'
            )
        );

        $this->hasOne('Acl_GroupRecord as acl', array(
            'local' => 'id',
            'foreign' => 'group_id'
        ));
    }

    public function getParentOptions()
    {
        $tree = Doctrine_Core::getTable(__CLASS__)->getTree();
        $nodes = $tree->fetchTree();
        if ($nodes) {
            foreach ($nodes as $node) {
                $options[$node['id']] = str_repeat('--', $node['level']) . $node['name'];
            }
            return $options;
        }
        return array();
    }

    public function preInsert($event)
    {
        $this->created_at = date('Y-m-d H:i:s', time());

        $this->updated_at = $this->created_at;

        $this->created_by = Qwin::getInstance()->user()->offsetGet('id');

        $this->updated_by = $this->created_by;
    }

    public function preUpdate($event)
    {
        $this->updated_at = date('Y-m-d H:i:s', time());

        $this->updated_by = Qwin::getInstance()->user()->offsetGet('id');
    }
}