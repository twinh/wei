<?php
/**
 * list
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
 * @since       2011-09-19 14:36:42
 */

return array(
    'fields' => array(
        'id' => array(
            'hidden' => true,
        ),
        'title' => array(

        ),
        'tags' => array(
            '_link' => true,
        ),
        'code' => array(

        ),
//        'created_at' => array(
//
//        ),
        'created_by' => array(

        ),
        'updated_at' => array(

        ),
        'updated_by' => array(

        ),
        'is_deleted' => array(

        ),
        'operation' => array(

        ),
    ),
    'layout' => array(
        'id', 'title', 'tags', 'updated_at', 'operation',
    ),
);
