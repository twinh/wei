<?php
/**
 * Potential
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
 * @since       2011-01-05 16:08:05
 */

class Crm_Potential_Metadata extends Com_Metadata
{
    public function setMetadata()
    {
        $this->setAdvancedMetadata();
        $this->merge(array(
            'field' => array(
                'name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'closing_date' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
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
                                    'package' => 'Crm',
                                    'module' => 'Customer',
                                    'controller' => 'Customer',
                                    'list' => 'id,name,birthday,email,source',
                                ),
                                array(
                                    'name', 'id'
                                ),
                            ),
                        ),
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'potential-status',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'potential-status',
                        ),
                        'view' => 'list',
                    ),
                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'potential-type',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'potential-type',
                        ),
                        'view' => 'list',
                    ),
                ),
                'probability' => array(

                ),
                'next_step' => array(

                ),
                'expected_revenue' => array(

                ),
                'source' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'customer-source',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'customer-source',
                        ),
                        'view' => 'list',
                    ),
                ),
                'campaign_id' => array(
                    'basic' => array(
                        'title' => 'FLD_CAMPAIGN',
                        'group' => 1,
                    ),
                    'form' => array(
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'LBL_MODULE_OPPORTUNITY',
                                array(
                                    'package' => 'Crm',
                                    'module' => 'Opportunity',
                                    'controller' => 'Opportunity',
                                    'list' => 'id,name,status,type,start_time,end_time',
                                ),
                                array('name', 'id'),
                            ),
                        ),
                    ),
                ),
                'contact_id' => array(
                    'basic' => array(
                        'title' => 'FLD_CONTACT',
                        'group' => 1,
                    ),
                    'form' => array(
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'FLD_CONTACT',
                                array(
                                    'package' => 'Crm',
                                    'module' => 'Contact',
                                    'controller' => 'Contact',
                                    'list' => 'id,full_name,email,source,birthday',
                                ),
                                array('full_name', 'id'),
                            ),
                        ),
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'group' => 2,
                        'layout' => 2,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),
            ),
            'group' => array(
                0 => 'LBL_GROUP_BASIC_DATA',
                1 => 'LBL_GROUP_RELATED_DATA',
                2 => 'LBL_GROUP_DESCRIPTION_DATA',
            ),
            'layout' => array(

            ),
            'model' => array(
                'receiver' => array(
                    'module' => 'com/member',
                    'alias' => 'receiver',
                    'type' => 'view',
                    'local' => 'assign_to',
                    'foreign' => 'id',
                    'fieldMap' => array(
                        'assign_to' => 'username',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'potential',
                'order' => array(
                    'date_created', 'DESC',
                ),
                'defaultWhere' => array(
                    array('is_deleted', 0),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_POTENTIAL',
                'icon' => 'buy',
                'tableLayout' => 1,
                'useTrash' => true,
                'mainField' => 'name',
            ),
        ));
    }

    public function sanitiseEditCustomerId($value, $name, $data, $dataCopy)
    {
        Crm_Helper::sanitisePopupCustomer($value, $name, 'name', $this);
        return $value;
    }

    public function sanitiseEditCampaignId($value, $name, $data, $dataCopy)
    {
        Crm_Helper::sanitisePopupOpportunity($value, $name, 'name', $this);
        return $value;
    }

    public function sanitiseEditContactId($value, $name, $data, $dataCopy)
    {
        Crm_Helper::sanitisePopupContact($value, $name, 'first_name', $this);
        return $value;
    }
}
