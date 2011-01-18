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
 * @since       2011-01-04 23:22:06
 */

class Crm_Customer_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_NUMBER' => '编号',
            'LBL_FIELD_SOURCE' => '来源',
            'LBL_FIELD_GRADE' => '等级',
            'LBL_FIELD_STATE' => '状态',
            'LBL_FIELD_QQ' => 'QQ',
            'LBL_FIELD_MSN' => 'Msn',
            'LBL_FIELD_SKYPE' => 'Skype',
            'LBL_FIELD_OFFICE_PHONE' => '办公电话',
            'LBL_FIELD_PHONE' => '个人电话',
            'LBL_FIELD_FAX' => '传真',
            'LBL_FIELD_WEBSITE' => '网站',
            'LBL_FIELD_COMPANY_ID' => '公司编号',
            'LBL_FIELD_BILL_COUNTRY' => '付款国家',
            'LBL_FIELD_BILL_PROVINCE' => '付款省份',
            'LBL_FIELD_BILL_CITY' => '付款城市',
            'LBL_FIELD_BILL_STREET' => '付款街道',
            'LBL_FIELD_BILL_ZIP' => '付款邮政编码',
            'LBL_FIELD_SHIP_COUNTRY' => '收货国家',
            'LBL_FIELD_SHIP_PROVINCE' => '收货省份',
            'LBL_FIELD_SHIP_CITY' => '收货城市',
            'LBL_FIELD_SHIP_STREET' => '收货街道',
            'LBL_FIELD_SHIP_ZIP' => '收货邮政编码',
            'LBL_FIELD_BANK_NAME' => '开户行',
            'LBL_FIELD_BANK_ACCOUNT_NAME' => '开户名称',
            'LBL_FIELD_BANK_ACCOUNT_ID' => '银行账号',
            'LBL_FIELD_PAYMENT_TYPE' => '支付方式',
            'LBL_FIELD_PAYMENT_CREDIT' => '信用额度',

            'LBL_GROUP_CONTACT_DATA' => '联系资料',
            'LBL_GROUP_ADDRESS_DATA' => '地址资料',
            'LBL_GROUP_BANK_DATA' => '银行财务资料',

            'LBL_ACTION_LEAD' => '潜在客户',

            'LBL_MODULE_CUSTOMER' => '客户',

            'LBL_FIELD_CARE_AT' => '关怀时间',
            'LBL_FIELD_CUSTOMER_ID' => '客户编号',
            'LBL_FIELD_FEEDBACK' => '反馈内容',

            'LBL_MODULE_CUSTOMERCARE' => '客户关怀',
        );
    }
}