<?php
/**
 * Email
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
 * @subpackage  Email
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-20 10:37:51
 */

class Trex_Email_Metadata_Email extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setIdMetadata()
            ->setCreatedData()
            ->setOperationMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'from' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                        ),
                    ),
                ),
                'from_name' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'to' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                        ),
                    ),
                ),
                'to_name' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'subject' => array(

                ),
                'content' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'result' => array(

                ),
            ),
            'model' => array(

            ),
            'db' => array(
                'table' => 'email',
                'order' => array(
                    array('date_created', 'DESC')
                )
            ),
            'page' => array(
                'title' => 'LBL_MODULE_EMAIL',
            ),
        ));
    }
}