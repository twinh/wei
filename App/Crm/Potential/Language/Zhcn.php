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
 * @since       2011-01-05 16:06:45
 */

class Crm_Potential_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_CLOSING_DATE' => '预计成交日期',
            'LBL_FIELD_CUSTOMER_ID' => '客户编号',
            'LBL_FIELD_PROBABILITY' => '可能性(%)',
            'LBL_FIELD_NEXT_STEP' => '下一步',
            'LBL_FIELD_EXPECTED_REVENUE' => '期望收益',
            'LBL_FIELD_SOURCE' => '来源',
            'LBL_FIELD_CAMPAIGN_ID' => '市场活动编号',
            'LBL_FIELD_CAMPAIGN' => '市场活动',
            'LBL_FIELD_CONTACT_ID' => '联系人编号',
            'LBL_FIELD_CONTACT' => '联系人',

            'LBL_GROUP_RELATED_DATA' => '关联资料',

            'LBL_MODULE_POTENTIAL' => '商机',
        );
    }
}