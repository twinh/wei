<?php
/**
 * Customer
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
 * @subpackage  Customer
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-08 10:56:57
 */

class Trex_Customer_Metadata_Customer extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'contact_id' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'name' => array(

                ),
            ),
            'model' => array(
                'contact' => array(
                    'name' => 'Trex_Contact_Model_Contact',
                    'alias' => 'contact',
                    'metadata' => 'Trex_Contact_Metadata_Contact',
                    'local' => 'contact_id',
                    'foreign' => 'id',
                ),
            ),
            'db' => array(
                'table' => 'customer',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CUSTOMER',
            ),
        ));
    }
}
