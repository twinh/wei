<?php
/**
 * Zhcn
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
 * @since       2011-01-05 17:57:58
 */

class Crm_Contact_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_ENGLISH_NAME' => '英文名称',
            'LBL_FIELD_CUSTOMER_ID' => '客户编号',
            'LBL_FIELD_SOURCE' => '来源',
            'LBL_FIELD_DEPARTMENT' => '部门',
            'LBL_FIELD_WEBSITE' => '网站',
            'LBL_FIELD_HOBBY' => '爱好',
            'LBL_FIELD_FAX' => '传真',
            'LBL_FIELD_HOME_PHONE' => '家庭电话',
            'LBL_FIELD_ASSISTANT_PHONE' => '助理电话',
            'LBL_FIELD_OTHER_PHONE' => '其他电话',
            'LBL_FIELD_QQ' => 'QQ',
            'LBL_FIELD_MSN' => 'MSN',
            'LBL_FIELD_SKYPE' => 'Skype',
            'LBL_FIELD_MAILING_COUNTRY' => '国家',
            'LBL_FIELD_MAILING_PROVINCE' => '省份',
            'LBL_FIELD_MAILING_CITY' => '城市',
            'LBL_FIELD_MAILING_STREET' => '地址',
            'LBL_FIELD_MAILING_POSTBOX' => '信箱',
            'LBL_FIELD_MAILING_ZIP' => '邮政编码',
            'LBL_FIELD_OTHER_COUNTRY' => '其他国家',
            'LBL_FIELD_OTHER_PROVINCE' => '其他省份',
            'LBL_FIELD_OTHER_CITY' => '其他城市',
            'LBL_FIELD_OTHER_STREET' => '其他地址',
            'LBL_FIELD_OTHER_POSTBOX' => '其他信箱',
            'LBL_FIELD_OTHER_ZIP' => '其他邮政编码',
            'LBL_FIELD_MOBILE' => '手机',
            'LBL_FIELD_SUPERIOR' => '上级',

            'LBL_GROUP_ADDRESS_DATA' => '地址资料',
            'LBL_GROUP_CONTACT_DATA' => '联系资料',
            'LBL_GROUP_OTHER_DATA' => '其他资料',

            'LBL_MODULE_CONTACT' => '联系人',
        );
        // todo
        $this->_data['LBL_FIELD_TITLE'] = '职务';
    }
}