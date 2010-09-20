<?php
/**
 * zhcn
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
 * @package     Trex
 * @subpackage  Projec
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-6-12 17:01:47 utf-8 中文
 * @since     2010-6-12 17:01:47 utf-8 中文
 */

class Trex_Project_Language_Enus extends Trex_Language_Enus
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_START_DATE' => '开始日期',
            'LBL_FIELD_PLANED_END_DATE' => '计划结束日期',
            'LBL_FIELD_END_TIME' => '结束日期',
            'LBL_FIELD_CODE' => '代号',
            'LBL_FIELD_FROM' => '来源',
            'LBL_FIELD_PARENT_PROJECT_NAME' => '父项目',
            'LBL_FIELD_STATUS_OPERATION' => '状态操作',
            'LBL_FIELD_INTRODUCER' => '介绍人',
            'LBL_FIELD_CUSTOMER_ID' => '客户编号',
            'LBL_FIELD_MONEY' => '费用',
            'LBL_FIELD_DELAY_REASON' => '超时原因',
            'LBL_FIELD_STATUS_DESCRIPTION' => '状态描述',
            'LBL_FIELD_AMOUNT' => '总数',
            'LBL_FIELD_DATE' => '日期',
            'LBL_FIELD_FEEDBACK_NAME' => '反馈名称',

            'LBL_FIELD_PROJECT_NAME' => '项目名称',
            'LBL_FIELD_PROJECT' => '项目',
            'LBL_FIELD_PRIORITY' => '优先权',
            'LBL_FIELD_SEVERITY' => '严重性',
            'LBL_FIELD_REPRODUCIBILITY' => '复现性',
            'LBL_FIELD_STATUS' => '状态',
            'LBL_FIELD_TICKET_NAME' => '问题名称',

            'LBL_ACTION_ADD_TICKET' => '添加问题',
            'LBL_ACTION_EDIT_STATUS' => '编辑状态',
            'LBL_ACTION_VIEW_STATUS' => '查看状态',

            'LBL_GROUP_STATUS_DATA' => '状态资料',
            'LBL_GROUP_BUSINESS_DATA' => '商务资料',

            'LBL_MODULE_PROJECT' => '项目',
            'LBL_MODULE_PROJECT_STATUS' => '项目状态',
            'LBL_MODULE_PROJECT_FEATURE' => '项目特性',
            'LBL_MODULE_PROJECT_TICKET' => '项目问题',
            'LBL_MODULE_PROJECT_MONEY' => '项目费用',
            'LBL_MODULE_PROJECT_FEEDBACK' => '项目费用',
            'LBL_MODULE_PROJECT_DOCUMENT' => '项目文档',
            'LBL_MODULE_PROJECT_TICKET_STATUS' => '项目问题状态',
        );
    }
}