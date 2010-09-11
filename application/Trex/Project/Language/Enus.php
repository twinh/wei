<?php
/**
 * en-Us
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
 * @since       2010-07-09 10:50:03
 */

class Trex_Project_Language_Enus extends Trex_Language
{
    public function __construct()
    {
        $this->_data += array(
            'LBL_FIELD_START_DATE' => 'Start Date',
            'LBL_FIELD_PLANED_END_DATE' => 'Planed End Date',
            'LBL_FIELD_END_TIME' => 'End Time',
            'LBL_FIELD_CODE' => 'Code',
            'LBL_FIELD_FROM' => 'From',
            'LBL_FIELD_PARENT_PROJECT_NAME' => 'Parent Project',
            'LBL_FIELD_STATUS_OPERATION' => 'Status Operation',
            'LBL_FIELD_INTRODUCER' => 'Introducer',
            'LBL_FIELD_CUSTOMER_ID' => 'Customer Id',
            'LBL_FIELD_MONEY' => 'Money',
            'LBL_FIELD_DELAY_REASON' => 'Delay Reason',
            'LBL_FIELD_STATUS_DESCRIPTION' => 'Status Description',
            'LBL_FIELD_TYPE' => 'Type',
            'LBL_FIELD_AMOUNT' => 'Amount',
            'LBL_FIELD_DATE' => 'Date',
            'LBL_FIELD_FEEDBACK_NAME' => 'Feedback Name',

            'LBL_FIELD_PROJECT_NAME' => 'Project Name',
            'LBL_FIELD_PROJECT' => 'Project',
            'LBL_FIELD_PRIORITY' => 'Priority',
            'LBL_FIELD_SEVERITY' => 'Severity',
            'LBL_FIELD_REPRODUCIBILITY' => 'Reproducibility',
            'LBL_FIELD_STATUS' => 'Status',
            'LBL_FIELD_TICKET_NAME' => 'Ticket Name',

            'LBL_ACTION_ADD_TICKET' => 'Add Ticket',
            'LBL_ACTION_EDIT_STATUS' => 'Edit Status',
            'LBL_ACTION_VIEW_STATUS' => 'View Status',

            'LBL_GROUP_STATUS_DATA' => 'Status Data',
            'LBL_GROUP_BUSINESS_DATA' => 'Business Data',

            'LBL_MODULE_PROJECT' => 'Project',
            'LBL_MODULE_PROJECT_STATUS' => 'Project Status',
            'LBL_MODULE_PROJECT_FEATURE' => 'Project Feature',
            'LBL_MODULE_PROJECT_TICKET' => 'Project Ticket',
            'LBL_MODULE_PROJECT_MONEY' => 'Project Money',
            'LBL_MODULE_PROJECT_FEEDBACK' => 'Project Feedback',
            'LBL_MODULE_PROJECT_DOCUMENT' => 'Project Document',
            'LBL_MODULE_PROJECT_TICKET_STATUS' => 'Project Ticket Status',
        );
    }
}