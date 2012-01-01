<?php

/**
 * List
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
 * @since       2011-08-14 10:10:23
 */
return array(
    'sortorder' => 'id',
    'colNames' => array(
        
    ),
    'colModel' => array(
        array(
            'name' => 'id',
            'index' => 'id',
            'link' => false,
            'hidden' => true,
        ),
        array(
            'name' => 'group_id',
            'index' => 'group_id',
            '_link' => true,
            '_relation' => array(
                'module' => 'member/group',
                'alias' => 'group',
                'field' => 'id',
                'display' => 'name',
            ),
        ),
        array(
            'name' => 'username',
            'index' => 'username',
        ),
        array(
            'name' => 'email',
            'index' => 'email',
        ),
        array(
            'name' => 'sex',
            'index' => 'sex',
            '_link' => true,
            '_sanitiser' => array(
                array(
                    array('Ide_Option_Widget', 'sanitise'),
                    'sex',
                ),
            ),
        ),
        array(
            'name' => 'date_modified',
            'index' => 'date_modified',
        ),
        array(
            'name' => 'operation',
            'index' => 'operation',
            'sortable' => false,
        ),
    ),
);
