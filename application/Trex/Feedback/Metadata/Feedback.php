<?php
/**
 * Feedback
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 * @package     Trex
 * @subpackage  Feedback
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-06 13:17:33
 */

class Trex_Feedback_Metadata_Feedback extends Trex_Metadata
{
    public function __construct()
    {
        $this->setIdMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'title' => array(

                ),
                'author' => array(

                ),
                'email' => array(
                    
                ),
                'content' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'order' => 1060,
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'basic' => array(
                        'order' => 1080,
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(

            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'feedback',
                'order' => array(
                    array('date_created', 'DESC'),
                )
            ),
            'page' => array(
                'title' => 'LBL_MODULE_FEEDBACK',
            ),
        ));
    }
}
