<?php
/**
 * TicketStatus
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
 * @since       2010-09-11 18:47:37
 */

class Trex_Project_Metadata_TicketStatus extends Trex_Metadata
{
    public function __construct()
    {
        $this->setIdMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'ticket_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TICKET_NAME',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'ticket_name' => array(
                    'form' => array(
                        '_type' => 'plain',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                        'isDbField' => 0,
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'ticket-status',
                        ),
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
                'created_by' => array(
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'date_created' => array(
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),
            ),
            'model' => array(
                'ticket' => array(
                    'name' => 'Trex_Project_Model_Ticket',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Ticket',
                    'type' => 'hasOne',
                    'local' => 'ticket_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'ticket_id' => 'title',
                    ),
                ),
                'member' => array(
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
            ),
            'db' => array(
                'table' => 'project_ticket_status',
                'order' => array(
                    array('date_created', 'ASC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_TICKET_STATUS',
            ),
        ));
    }
}
