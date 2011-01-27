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
 * @since       2011-01-05 14:13:09
 */

class Crm_Opportunity_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_START_TIME' => '开始时间',
            'LBL_FIELD_END_TIME' => '结束时间',
            'LBL_FIELD_PRODUCT_ID' => '产品编号',
            'LBL_FIELD_BUDGETED_COST' => '预算成本',
            'LBL_FIELD_ACTUAL_COST' => '实际成本',
            'LBL_FIELD_EXPECTED_RESPONSE' => '预期反应',
            'LBL_FIELD_ACTUAL_RESPONSE' => '实际反应',
            'LBL_FIELD_EXPECTED_REVENUE' => '预期收入',
            'LBL_FIELD_ACTUAL_REVENUE' => '实际收入',
            'LBL_FIELD_EXPECTED_SALES_COUNT' => '预期销售数量',
            'LBL_FIELD_ACTUAL_SALES_COUNT' => '实际销售数量',
            'LBL_FIELD_EXPECTED_RESPONSE_COUNT' => '预期反应数量',
            'LBL_FIELD_ACTUAL_RESPONSE_COUNT' => '实际反应数量',
            'LBL_FIELD_EXPECTED_ROI' => '预期回报率',
            'LBL_FIELD_ACTUAL_ROI' => '实际回报率',

            'LBL_GROUP_STATUS_DATA' => '状况资料',

            'LBL_MODULE_OPPORTUNITY' => '市场活动'
        );
    }
}