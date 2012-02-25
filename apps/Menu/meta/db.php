<?php
/**
 * db
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
 * @since       2011-7-11 14:41:17
 */

return array(
    'fields' => array(
        'id' => array(
            
        ),
        'category_id' => array(
            
        ),
        'title' => array(
            
        ),
        'url' => array(
            
        ),
        'target' => array(
            
        ),
        'order' => array(
            
        ),
    ),
    'table' => 'member_menu',
    'relations' => array(
        'menu' => array(
            'module'    => 'member/menu',
            'alias'     => 'menu',
            'db'        => 'db',
            'relation'  => 'hasOne',
            'local'     => 'category_id',
            'foreign'   => 'id',
            'type'      => 'db',
            'fieldMap'  => array(), // ?是否仍需要
            'enabled'   => true,
        ),
    ),
);
