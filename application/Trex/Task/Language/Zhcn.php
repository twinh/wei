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
 * @subpackage  Task
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-20 17:26:14
 */

class Trex_Task_Language_Zhcn extends Trex_Language_Zhcn
{
    public function  __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_STATUS' => '状态',
            'LBL_FIELD_ASSIGN_TO' => '分配给',
            'LBL_FIELD_ASSIGN_BY' => '分配者',
            'LBL_FIELD_TASK_NAME' => '任务名称',
            'LBL_FIELD_IS_POST_EMAIL' => '发送邮件?',


            'LBL_ACTION_ASSIGN_TO' => '分配任务',
            'LBL_ACTION_RESOLVED' => '我已解决该任务.',
            'LBL_ACTION_CHECKED' => '我已检查该任务.',
            'LBL_ACTION_CLOSED' => '关闭任务.',


            'MSG_NOT_ASSIGN_TO_YOU' => '该任务不是分配给您.',
            'MSG_NOT_ASSIGN_BY_YOU' => '该任务不是由您分配.',
            'MSG_TASK_HAS_ASSIGNED' => '该任务已分配完成.',
            'MSG_NOT_CREATED_BY_YOU' => '该任务不是由您创建.',
            'MSG_NOT_FOLLOW_FLOW' => '您是否忘记一些操作的流程?',

            'LBL_MODULE_TASK' => '任务',
            'LBL_MODULE_TASK_STATUS' => '任务状态',
            'LBL_MODULE_TASK_ASSIGN_TO_ME' => '分配给我的任务',
            'LBL_MODULE_TASK_ASSIGN_BY_ME' => '我分配的任务',
        );
    }
}
