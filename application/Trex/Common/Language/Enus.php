<?php
/**
 * Enus
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
 * @since       2010-08-16 19:24:06
 */

class Default_Common_Language_Enus extends Default_Language
{
    public function __construct()
    {
        $this->_data += array(
            '' => 'NULL',

            // Qwin's information
            'LBL_QWIN' => 'Qwin',
            'LBL_QWIN_VERSION' => '3.0Beta',

            // User's actions in browser
            'LBL_ACTION_LIST' => 'List',
            'LBL_ACTION_ADD' => 'Add',
            'LBL_ACTION_EDIT' => 'Edit',
            'LBL_ACTION_DELETE' => 'Delete',
            'LBL_ACTION_VIEW' => 'View',
            'LBL_ACTION_COPY' => 'Copy',
            'LBL_ACTION_FILTER' => 'Filter',
            'LBL_ACTION_RETURN' => 'Return',
            'LBL_ACTION_RESET' => 'Reset',
            'LBL_ACTION_SUBMIT' => 'Submit',
            'LBL_ACTION_APPLY' => 'Apply',
            'LBL_ACTION_RESTORE' => 'Restore',

            'LBL_DEFAULT' => 'Default',
            'LBL_OPERATION' => 'Operation',

            'LBL_DEFAULT' => 'Default',

            'LBL_SHORTCUT' => 'Shortcuts',
            'LBL_LAST_VIEWED' => 'Last viewed',

            // 页眉
            'LBL_STYLE' => 'Style',
            'LBL_THEME' => 'Theme',
            'LBL_WELCOME' => 'Welcome',
            'LBL_LOGOUT' => 'Logout',
            'LBL_TOOL' => 'Tool',
            'LBL_LANG' => 'Language',

            // field
            'LBL_FIELD_ID' => 'ID',
            'LBL_FIELD_NAME' => 'Name',
            'LBL_FIELD_NAMESPACE' => 'Namespace',
            'LBL_FIELD_TITLE' => 'Title',
            'LBL_FIELD_OPERATION' => 'Operation',
            'LBL_FIELD_DATE_CREATED' => 'Date Created',
            'LBL_FIELD_DATE_MODIFIED' => 'Date Modified',
            'LBL_FIELD_CAPTCHA' => 'Captcha',
            'LBL_FIELD_DESCRIPTION' => 'Description',
            'LBL_FIELD_CONTENT' => 'Content',
            'LBL_FIELD_CATEGORY_ID' => 'Category Id',
            'LBL_FIELD_CATEGORY_NAME' => 'Category Name',

            // group
            'LBL_GROUP_BASIC_DATA' => 'Basic Data',
            'LBL_GROUP_DETAIL_DATA' => 'Detail Data',
            'LBL_GROUP_DEFAULT_DATA' => 'Default Data',
            'LBL_SWITCH_DISPLAY_MODEL' => 'Switch display model',

            'MSG_CHOOSE_ONLY_ONE_ROW' => 'Please choose only one row!',
            'MSG_CHOOSE_AT_LEASE_ONE_ROW' => 'Please choose at lease one row!',
            'MSG_CONFIRM_TO_DELETE' => 'Confirm to delete,OK?',
            'MSG_ERROR_FIELD' => 'Error field: ',
            'MSG_ERROR_MSG' => 'Error message: ',
            'MSG_ERROR_CAPTCHA' => 'Captcha error.',
            'MSG_NO_RECORD' => 'The record isn\'t exists or has been deleted.',
            'MSG_OPERATE_SUCCESSFULLY' => 'Operate successfully!',
            'MSG_FUNCTION_DEVELOPTING' => 'The function is developing.',

            'LBL_HTML_TITLE' => 'Content Management System - Powered by Qwin Framework',
            'LBL_FOOTER_COPYRIGHT' => 'Powered by <a>Qwin Framework</a>. Copyright &copy; 2009-2010 <a>Twin</a>. All rights reserved.',
        );
    }
}
//