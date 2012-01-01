<?php
/**
 * formsample
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
 * @since       2011-05-18 11:04:39
 */

return array(
    'title' => array(
        
    ),
    'name' => array(
        
    ),
    'order' => array(
        
    ),
    'description' => array(
        '_type' => 'textarea',
    ),
    'dbField' => array(
        '_type' => 'radio',
        '_value' => 1,
        '_resourceGetter' => array(
            array('Ide_Option_Widget', 'get'),
            'yes-or-no',
        ),
    ),
    'dbQuery' => array(
        '_type' => 'radio',
        '_value' => 1,
        '_resourceGetter' => array(
            array('Ide_Option_Widget', 'get'),
            'yes-or-no',
        ),
    ),
    'urlQuery' => array(
        '_type' => 'radio',
        '_value' => 1,
        '_resourceGetter' => array(
            array('Ide_Option_Widget', 'get'),
            'yes-or-no',
        ),
    ),
    'readonly' => array(
        '_type' => 'radio',
        '_value' => 0, 
        '_resourceGetter' => array(
            array('Ide_Option_Widget', 'get'),
            'yes-or-no',
        ),
    ),
);
