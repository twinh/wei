<?php
/**
 * Care
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
 * @since       2011-01-18 11:46:12
 */

class Crm_Customer_Metadata_Care extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setAdvancedMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'care_at' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                ),
                'customer_id' => array(
                    'basic' => array(
                        'title' => 'LBL_MODULE_CUSTOMER',
                    ),
                    'form' => array(
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'LBL_MODULE_CUSTOMER',
                                array(
                                    'namespace' => 'Crm',
                                    'module' => 'Customer',
                                    'controller' => 'Customer',
                                    'qw-list' => 'id,name,birthday,email,source',
                                ),
                                array(
                                    'name', 'id'
                                ),
                            ),
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                        'isLink' => 1,
                    ),
                ),
                'contact_id' => array(
                    'basic' => array(
                        'title' => 'LBL_MODULE_CONTACT',
                    ),
                    'form' => array(
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'LBL_MODULE_CONTACT',
                                array(
                                    'namespace' => 'Crm',
                                    'module' => 'Contact',
                                    'controller' => 'Contact',
                                    'qw-list' => 'id,full_name,birthday,email,source',
                                ),
                                array(
                                    'full_name',
                                    'id'
                                ),
                            ),
                        ),
                    ),
                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'customer-care-type',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'customer-care-type',
                        ),
                        'view' => 'list',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                ),
                'content' => array(
                    'basic' => array(
                        'layout' => 2,
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),
                'feedback' => array(
                    'basic' => array(
                        'layout' => 2,
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'layout' => 2,
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),
            ),
            'group' => array(
                1 => 'LBL_GROUP_DETAIL_DATA'
            ),
            'layout' => array(

            ),
            'model' => array(
                'customer' => array(
                    'alias' => 'customer',
                    'type' => 'view',
                    'local' => 'customer_id',
                    'fieldMap' => array(
                        'customer_id' => 'name',
                    ),
                    'set' => array(
                        'namespace' => 'Crm',
                        'module' => 'Customer',
                        'controller' => 'Customer',
                    ),
                ),
            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'customer_care',
                'order' => array(
                    array('date_created', 'DESC')
                ),
                'defaultWhere' => array(
                    array('is_deleted', 0),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CUSTOMERCARE',
                'icon' => 'user',
                'tableLayout' => 1,
                'alias' => 'customer',
                'useRecycleBin' => true,
                'mainField' => 'name',
            ),
        ));
    }

    public function convertEditCustomerId($value, $name, $data, $dataCopy)
    {
        Crm_Helper::convertPopupCustomer($value, $name, 'name', $this);
        return $value;
    }

    public function convertEditContactId($value, $name, $data, $dataCopy)
    {
        Crm_Helper::convertPopupContact($value, $name, '', $this);
        return $value;
    }
}