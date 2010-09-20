<?php
/**
 * Status
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
 * @since       2010-07-09 14:58:27
 */

class Trex_Project_Metadata_Ticket extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'project_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PROJECT_NAME'
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
                ),
                'title' => array(

                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'ticket-type',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'ticket-type',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'ticket-type',
                        ),
                    ),
                ),
                'priority' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                    ),
                ),
                'severity' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                    ),
                ),
                'reproducibility' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
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
                            'ticket-status',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'ticket-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'ticket-status',
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
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'created_by' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CREATOR'
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'modified_by' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_MODIFIER'
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
            ),
            'model' => array(
                array(
                    'name' => 'Trex_Project_Model_Project',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Project',
                    'local' => 'project_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'project_id' => 'name',
                    ),
                ),
                array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'member',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'created_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'created_by' => 'username',
                    ),
                ),
                array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'member2',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'modified_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'modified_by' => 'username',
                    ),
                ),
                'status' => array(
                    'name' => 'Trex_Project_Model_TicketStatus',
                    'alias' => 'status',
                    'metadata' => 'Trex_Project_Metadata_TicketStatus',
                    'local' => 'id',
                    'foreign' => 'project_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'ticket_id',
                        'status' => 'status',
                        'date_modified' => 'date_created',
                        'status_description' => 'description',
                        'created_by' => 'created_by',
                    ),
                    'set' => array(
                        'namespace' => 'Trex',
                        'module' => 'Project',
                        'controller' => 'TicketStatus',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'project_ticket',
                'order' => array(
                    array('date_created', 'DESC')
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_TICKET',
            ),
        ));
        $this->field->set('operation.list.width', 180);
    }
}
