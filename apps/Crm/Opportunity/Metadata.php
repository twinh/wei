<?php
/**
 * Opportunity
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
 * @since       2011-01-05 14:13:37
 */

class Crm_Opportunity_Meta extends Com_Meta
{
    public function setMeta()
    {
        $this->setAdvancedMeta();
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
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'opportunity-status',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'opportunity-status',
                        ),
                        'view' => 'list',
                    ),
                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'opportunity-type',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'opportunity-type',
                        ),
                        'view' => 'list',
                    ),
                ),
                'parent_id' => array(
                    'form' => array(
                        '_widget' => array(
                            array(
                                array('PopupGrid_Widget', 'render'),
                                array(array(
                                    'title'  => 'LBL_MODULE_OPPORTUNITY',
                                    'module' => 'crm/opportunity',
                                    'list' => 'id,name,status,type,start_time,end_time',
                                    'fields' => array('name', 'id'),
                                )),
                            ),
                        ),
                    ),
                ),
                'start_time' => array(
                    'form' => array(
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_FromToDatepicker', 'render'),
                                array('start_time', 'end_time')
                            )
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'end_time' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'product_id' => array(

                ),
                'budgeted_cost' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_cost' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'expected_response' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_response' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'expected_revenue' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_revenue' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'expected_sales_count' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_sales_count' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'expected_response_count' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_response_count' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'expected_roi' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
                'actual_roi' => array(
                    'basic' => array(
                        'group' => 1,
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
                1 => 'LBL_GROUP_STATUS_DATA',
                2 => 'LBL_GROUP_DESCRIPTION_DATA'
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
                'table' => 'opportunity',
                'order' => array(
                    array('date_created', 'DESC')
                ),
                'defaultWhere' => array(
                    array('is_deleted', 0),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_OPPORTUNITY',
                'icon' => 'present',
                'tableLayout' => 1,
                'useTrash' => true,
                'mainField' => 'name',
            ),
        ));
    }

    public function sanitiseEditParentId($value, $name, $data, $dataCopy)
    {
        $data = Com_Meta::getQueryByModule('crm/opportunity')
            ->select('name')
            ->where('id = ?', $value)
            ->fetchOne();

        $this['field'][$name]['form']['_value2'] = $data['name'];
        return $value;
    }
}
