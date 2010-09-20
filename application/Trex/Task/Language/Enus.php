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
 * @subpackage  Task
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-19 09:34:20
 */

class Trex_Task_Language_Enus extends Trex_Language_Enus
{
    public function  __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_STATUS' => 'Status',
            'LBL_FIELD_ASSIGN_TO' => 'Assign To',
            'LBL_FIELD_ASSIGN_BY' => 'Assign By',
            'LBL_FIELD_TASK_NAME' => 'Task name',
            'LBL_FIELD_IS_POST_EMAIL' => 'Post Email?',


            'LBL_ACTION_ASSIGN_TO' => 'Assign to',
            'LBL_ACTION_RESOLVED' => 'I have resolved the task.',
            'LBL_ACTION_CHECKED' => 'I have checked the task.',
            'LBL_ACTION_CLOSED' => 'Close the task.',


            'MSG_NOT_ASSIGN_TO_YOU' => 'The task is not assign to you.',
            'MSG_NOT_ASSIGN_BY_YOU' => 'The task is not assign by you.',
            'MSG_TASK_HAS_ASSIGNED' => 'The task has been assigned.',
            'MSG_NOT_CREATED_BY_YOU' => 'Ths task is not created by you.',
            'MSG_NOT_FOLLOW_FLOW' => 'Do you forget anything to do before this?',

            'LBL_MODULE_TASK' => 'Task',
            'LBL_MODULE_TASK_STATUS' => 'Task Status',
            'LBL_MODULE_TASK_ASSIGN_TO_ME' => 'Task Assign to me',
            'LBL_MODULE_TASK_ASSIGN_BY_ME' => 'Task Assign by me',
        );
    }
}