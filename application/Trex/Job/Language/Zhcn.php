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
 * @package     Trex
 * @subpackage  Job
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-20 11:34:03
 */

class Trex_Job_Language_Zhcn extends Trex_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data = array(
            'LBL_FIELD_TYPE' => '职位类别',
            'LBL_FIELD_WORK_TYPE' => '职位类型',
            'LBL_FIELD_TITLE' => '职位名称',
            'LBL_FIELD_NUMBER' => '招聘人数',
            'LBL_FIELD_EDUCATION' => '学历要求',
            'LBL_FIELD_WORK_SENIORITY' => '工作年限',
            'LBL_FIELD_SALARY' => '薪资范围',
            'LBL_FIELD_SALARY_FROM' => '薪资范围(从)',
            'LBL_FIELD_SALARY_TO' => '薪资范围(到)',
            'LBL_FIELD_WORKING_PLACE' => '工作地点',
            'LBL_FIELD_DESCRIPTION' => '补充说明',
            'LBL_FIELD_CONTACTER' => '联系人',
            'LBL_FIELD_PHONE' => '手机或电话',

            'LBL_MODULE_JOB' => '兼职招聘',
        ) + $this->_data;
    }
}