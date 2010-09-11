<?php
/**
 * Project
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
 * @subpackage  Project
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-12 17:12:14
 */

class Trex_Project_Metadata_Project extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'parent_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PARENT_PROJECT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Project',
                                'controller' => 'Project',
                            ),
                            NULL,
                            array('id', 'parent_id', 'name')
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
                'name' => array(
                    'validator' => array(
                        'required',
                    ),
                ),
                'code' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'introducer' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_BUSINESS_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'customer_id' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_BUSINESS_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Customer',
                                'controller' => 'Customer',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'money' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_BUSINESS_DATA',
                    ),
                ),
                'status' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_STATUS_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'project-status',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'project-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'project-status',
                        ),
                    ),
                ),
                'status_description' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_STATUS_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 0,
                    ),
                ),
                'start_date' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                ),
                'planed_end_date' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                ),
                'end_time' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'delay_reason' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
                'project' => array(
                    'name' => 'Trex_Project_Model_Project',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Project',
                    'local' => 'parent_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'parent_id' => 'name',
                    ),
                ),
                'status' => array(
                    'name' => 'Trex_Project_Model_Status',
                    'alias' => 'status',
                    'metadata' => 'Trex_Project_Metadata_Status',
                    'local' => 'id',
                    'foreign' => 'project_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'project_id',
                        'status' => 'status',
                        'date_modified' => 'date_created',
                        'status_description' => 'description',
                        'created_by' => 'created_by',
                    ),
                    'set' => array(
                        'namespace' => 'Trex',
                        'module' => 'Project',
                        'controller' => 'Status',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'project',
                'order' => array(
                    array('start_date', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT',
            ),
        ));
        $this->field->set('operation.list.width', 200);
        $this->field->set('date_created.attr.isList', 0);
        $this->field->set('date_modified.attr.isList', 0);
    }
}
