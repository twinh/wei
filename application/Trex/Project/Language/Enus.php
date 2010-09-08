<?php
/**
 * en
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-7-9 10:50:03
 * @since     2010-7-9 10:50:03
 */

class Trex_Project_Language_Enus extends Trex_Language
{
    public function __construct()
    {
        $this->_data += array(
            'LBL_FIELD_START_TIME' => 'Start Time',
            'LBL_FIELD_PLANED_END_TIME' => 'Planed End Time',
            'LBL_FIELD_END_TIME' => 'End Time',
            'LBL_FIELD_CODE' => 'Code',
            'LBL_FIELD_FROM' => 'From',
            'LBL_FIELD_PARENT_PROJECT_NAME' => 'Parent Project',
            'LBL_FIELD_STATUS_OPERATION' => 'Status Operation',
            'LBL_FIELD_INTRODUCER' => 'Introducer',
            'LBL_FIELD_CUSTOMER_ID' => 'Customer Id',
            'LBL_FIELD_MONEY' => 'Money',
            'LBL_FIELD_DELAY_REASON' => 'Delay Reason',

            'LBL_FIELD_PROJECT_NAME' => 'Project Name',
            'LBL_FIELD_PROJECT' => 'Project',
            'LBL_FIELD_PRIORITY' => 'Priority',
            'LBL_FIELD_SEVERITY' => 'Severity',
            'LBL_FIELD_REPRODUCIBILITY' => 'Reproducibility',
            'LBL_FIELD_STATUS' => 'Status',
            'LBL_FIELD_CREATED_BY' => 'Created By',

            'LBL_ACTION_ADD_BUG' => 'Add Bug',
            'LBL_ACTION_EDIT_STATUS' => 'Edit Status',
            'LBL_ACTION_VIEW_STATUS' => 'View Status',

            'LBL_MODULE_PROJECT' => 'Project',
            'LBL_MODULE_PROJECT_STATUS' => 'Project Status',
            'LBL_MODULE_PROJECT_FEATURE' => 'Feature',
            'LBL_MODULE_PROJECT_BUG' => 'Bug',
        );
    }
}