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
 * @package     Trex
 * @subpackage  Common
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 19:24:06
 */

class Trex_Common_Language_Enus extends Trex_Language
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
            'LBL_ACTION_REDIRECT' => 'Redirect',
            'LBL_ACTION_UPDATE' => 'Update',

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
            'LBL_MESSAGE' => 'Message',
            'LBL_MEMBER_CENTER' => 'Member Center',
            'LBL_MANAGEMENT' => 'Management',

            // field
            'LBL_FIELD_ID' => 'Id',
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
            'LBL_FIELD_CREATOR' => 'Creator',
            'LBL_FIELD_MODIFIER' => 'Modifiter',
            'LBL_FIELD_CREATED_BY' => 'Created By',
            'LBL_FIELD_MODIFIED_BY' => 'Modified By',
            'LBL_FIELD_SUMMARY' => 'Summary',
            'LBL_FIELD_TYPE' => 'Type',
            'LBL_FIELD_PARENT_NAME' => 'Parent Name',
            'LBL_FIELD_PARENT_ID' => 'Parent Id',

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
            'MSG_CLICK_TO_REDIRECT' => 'It is going to redirect to a new page in 3 seconds, click the "Redirect" button to redirect at once.',
            // 3秒后跳转到新的页面,点击"跳转"按钮直接跳转.
            'MSG_NOT_ALLOW_DELETE' =>'It is not allowed to delete the member.',

            'MSG_FILE_NOT_EXISTS' => 'The file is not exists',

            'LBL_HTML_TITLE' => 'Content Management System - Powered by Qwin Framework',
            'LBL_FOOTER_COPYRIGHT' => 'Powered by <a>Qwin Framework</a>. Copyright &copy; 2009-2010 <a>Twin</a>. All rights reserved.',


            // TODO 加载其他模块时,同时加载他们的语言类
            'LBL_FIELD_CONTACT_ID' => 'Contact Id',
            'LBL_FIELD_FIRST_NAME' => 'First Name',
            'LBL_FIELD_LAST_NAME' => 'Last Name',
            'LBL_FIELD_NICKNAME' => 'Nickname',
            'LBL_FIELD_PHOTO' => 'Photo',
            'LBL_FIELD_RELATION' => 'Relation',
            'LBL_FIELD_BIRTHDAY' => 'Birthday',
            'LBL_FIELD_EMAIL' => 'Email',
            'LBL_FIELD_TELEPHONE' => 'Telephone',
            'LBL_FIELD_MOBILE' => 'Mobile',
            'LBL_FIELD_SEX' => 'Sex',
            'LBL_FIELD_HOMEPAGE' => 'Homepage',
            'LBL_FIELD_ADDRESS' => 'Address',

            'MSG_VALIDATOR_REQUIRED' => 'This field is required.',
            'MSG_VALIDATOR_REMOTE' => 'Please fix this field.',
            'MSG_VALIDATOR_EMAIL' => 'Please enter a valid email address.',
            'MSG_VALIDATOR_URL' => 'Please enter a valid URL.',
            'MSG_VALIDATOR_DATE' => 'Please enter a valid date.',
            'MSG_VALIDATOR_DATEISO' => 'Please enter a valid date (ISO).',
            'MSG_VALIDATOR_NUMBER' => 'Please enter a valid number.',
            'MSG_VALIDATOR_DIGITS' => 'Please enter only digits.',
            'MSG_VALIDATOR_CREDITCARD' => 'Please enter a valid credit card number.',
            'MSG_VALIDATOR_EQUALTO' => 'Please enter the same value again.',
            'MSG_VALIDATOR_ACCEPT' => 'Please enter a value with a valid extension.',
            'MSG_VALIDATOR_MAXLENGTH' => 'Please enter no more than {0} characters.',
            'MSG_VALIDATOR_MINLENGTH' => 'Please enter at least {0} characters.',
            'MSG_VALIDATOR_RANGELENGTH' => 'Please enter a value between {0} and {1} characters long.',
            'MSG_VALIDATOR_RANGE' => 'Please enter a value between {0} and {1}.',
            'MSG_VALIDATOR_MAX' => 'Please enter a value less than or equal to {0}.',
            'MSG_VALIDATOR_MIN' => 'Please enter a value greater than or equal to {0}.',
        );
    }
}