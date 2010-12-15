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
 * @package     Common
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-23 17:24:04
 */

class Common_Member_Language_Enus extends Common_Language_Enus
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_GROUP_ID' => 'Group',
            'LBL_FIELD_USERNAME' => 'Username',
            'LBL_FIELD_PASSWORD' => 'Password',
            'LBL_FIELD_EMAIL' => 'Email',
            'LBL_FIELD_NICKNAME' => 'Nickname',
            'LBL_FIELD_SEX' => 'Sex',
            'LBL_FIELD_REG_TIME' => 'Reg Time',
            'LBL_FIELD_REG_IP' => 'Reg Ip',
            'LBL_FIELD_EMAIL_FOREIGN_ID' => 'Email Foreign Id',
            'LBL_FIELD_EMAIL_ADDRESS' => 'Email Address',
            'LBL_FIELD_EMAIL_REMARK' => 'Email Remark',
            'LBL_FIELD_THEME_NAME' => 'Theme Name',
            'LBL_FIELD_LANG' => 'Language',
            'LBL_FIELD_NEW_PASSWORD' => 'New Password',
            'LBL_FIELD_OLD_PASSWORD' => 'Old Password',
            'LBL_FIELD_CONFIRM_PASSWORD' => 'Confirm Password',
            'LBL_FIELD_IMAGE_PATH' => 'Image Path',

            'LBL_FIELD_COMPANY' => 'Company',
            'LBL_FIELD_CUSTOMER_NAME' => 'Customer',
            'LBL_FIELD_AREA' => 'Area',
            'LBL_FIELD_DEPARTMENT' => 'Department',
            'LBL_FIELD_POSITION' => 'Position',
            'LBL_FIELD_TELEPHONE' => 'Telephone',
            'LBL_FIELD_FAX' => 'Fax',
            'LBL_FIELD_QQ' => 'QQ',
            'LBL_FIELD_MOBILE' => 'Mobile',
            'LBL_FIELD_BIRTHDAY' => 'Birthday',
            'LBL_FIELD_POSTCODE' => 'Postcode',
            'LBL_FIELD_MEMBER_ID' => 'Member Id',
            'LBL_FIELD_THEME' => 'Theme',
            'LBL_FIELD_LANGUAGE' => 'Language',
            'LBL_FIELD_IP' => 'Ip',

            'LBL_GROUP_BASIC_DATA' => 'Basic Data',
            'LBL_GROUP_DETAIL_DATA' => 'Detail Data',
            'LBL_GROUP_EMAIL_DATA' => 'Email Data',
            'LBL_GROUP_COMPANY_DATA' => 'Company Data',

            'LBL_MODULE_MEMBER' => 'Member',
            'LBL_MODULE_MEMBER_DETAIL' => 'Member Detail',
            'LBL_MODULE_MEMBER_GROUP' => 'Member Group',
            'LBL_MODULE_MEMBER_LOGINLOG' => 'Member Login Log',

            'LBL_THEME' => 'Theme',
            'LBL_LANGUAGE' => 'Language',
            'LBL_VIEW_DATA' => 'View Data',
            'LBL_EDIT_DATA' => 'Edit Data',
            'LBL_EDIT_PASSWORD' => 'Edit Password',
            'LBL_SWITCH_STYLE' => 'Switch Style',
            'LBL_SWITCH_LANGUAGE' => 'Switch Language',
            'LBL_ACTION_ALLOCATE_PERMISSION' => 'Allocate Permission',

            
            'LBL_LOGIN_TITLE' => 'Welcome to use the system',

            'LBL_ACTION_EDIT_PASSWORD' => 'Edit password',

            'MSG_ERROR_USERNAME_PASSWORD' => 'username or password error',
            'MSG_LOGINED' => 'You have login',
            'MSG_LOGOUTED' => 'You have logout',
            'MSG_NOT_LOGIN' => 'You have not login',
            'MSG_USERNAME_EXISTS' => 'the username has existed.',
            'MSG_OLD_PASSWORD_NOT_CORRECT' => 'The old password is not correct.',
            'MSG_MEMBER_NOT_ALLOW_DELETE' => 'System member is not allowed to delete.',
            'MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD' => 'It is not allowed to edit guest member\'s password.',
        );
    }
}
