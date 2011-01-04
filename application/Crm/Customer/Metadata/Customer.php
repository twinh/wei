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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-04 22:55:48
 */

class Crm_Customer_Metadata_Customer extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setCommonMetadata()
            ->parseMetadata(array(
                'field' => array(
                    'name' => array(
                        'validator' => array(
                            'rule' => array(
                                'required' => true,
                            ),
                        ),
                    ),
                    'number' => array(
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'sex' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'sex',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'sex',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                        ),
                    ),
                    'type' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'customer-type',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'customer-type',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                        ),
                    ),
                    'source' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'customer-source',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'customer-source',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                        ),
                    ),
                    'grade' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'customer-grade',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'customer-grade',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                        ),
                    ),
                    'state' => array(
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'customer-state',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'customer-state',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                        ),
                    ),
                    'email' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                    ),
                    'qq' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'msn' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'skype' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'office_phone' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                    ),
                    'phone' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'fax' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'website' => array(
                        'basic' => array(
                            'group' => 1,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'company_id' => array(
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bill_country' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'ship_country' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bill_province' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'ship_province' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bill_city' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'ship_city' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bill_street' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'ship_street' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bill_zip' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'ship_zip' => array(
                        'basic' => array(
                            'group' => 3,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bank_name' => array(
                        'basic' => array(
                            'group' => 4,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bank_account_name' => array(
                        'basic' => array(
                            'group' => 4,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'bank_account_id' => array(
                        'basic' => array(
                            'group' => 4,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'payment_type' => array(
                        'basic' => array(
                            'group' => 4,
                        ),
                        'form' => array(
                            '_type' => 'select',
                            '_resourceGetter' => array(
                                array('Project_Helper_CommonClass', 'get'),
                                'payment-type',
                            ),
                        ),
                        'converter' => array(
                            'list' => array(
                                array('Project_Helper_CommonClass', 'convert'),
                                'payment-type',
                            ),
                            'view' => 'list',
                        ),
                        'attr' => array(
                            'isLink' => 1,
                            'isList' => 0,
                        ),
                    ),
                    'payment_credit' => array(
                        'basic' => array(
                            'group' => 4,
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'assign_to' => array(
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                    'is_deleted' => array(
                        'form' => array(
                            '_type' => 'hidden',
                        ),
                        'attr' => array(
                            'isList' => 0,
                        ),
                    ),
                ),
                'group' => array(
                    0 => 'LBL_GROUP_BASIC_DATA',
                    1 => 'LBL_GROUP_CONTACT_DATA',
                    2 => 'LBL_GROUP_COMPANY_DATA',
                    3 => 'LBL_GROUP_ADDRESS_DATA',
                    4 => 'LBL_GROUP_BANK_DATA',
                    5 => 'LBL_GROUP_DESCRIPTION_DATA'
                ),
                'layout' => array(

                ),
                'model' => array(
                    /*'company' => array(
                        'alias' => 'detail',
                        'type' => 'db',
                        'local' => 'company_id',
                        'set' => array(
                            'namespace' => 'Crm',
                            'module' => 'Company',
                            'controller' => 'Company',
                        ),
                    ),*/
                ),
                'metadata' => array(

                ),
                'db' => array(
                    'table' => 'customer',
                    'order' => array(
                        array('date_created', 'DESC')
                    ),
                ),
                'page' => array(
                    'title' => 'LBL_MODULE_CUSTOMER',
                    'icon' => 'user',
                    'tableLayout' => 1,
                    'alias' => 'customer',
                    'letter' => 'C'
                ),
            ));
    }

    public function convertAddNumber($value, $name, $data, $dataCopy)
    {
        $count = $this->metaHelper->getQuery($this)->count() + 1;
        return $this['page']['letter'] . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}