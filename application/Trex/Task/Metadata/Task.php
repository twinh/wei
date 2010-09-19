<?php
/**
 * Task
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
 * @todo        分配报表,检查人员,前置任务,后置任务,一个任务分配给多个人
 */

class Trex_Task_Metadata_Task extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea'
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'task-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'task-status',
                        ),
                    ),
                    'attr' => array(
                        //'isListLink' => 1,
                    ),
                ),
                'assign_to' => array(
                     'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Member',
                                'controller' => 'Member',
                            ),
                            null,
                            array('id', null, 'username'),
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'notNull' => true,
                        ),
                    ),
                ),
                'assign_by' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
            ),
            'model' => array(
                'assign_by' => array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'assign-by',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'assign_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'assign_by' => 'username',
                    ),
                ),
                'assign_to' => array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'assign-to',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'assign_to',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'assign_to' => 'username',
                    ),
                ),
                'created_by' => array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'created-by',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'created_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'created_by' => 'username',
                    ),
                ),
                'modified_by' => array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'modified-by',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'modified_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'modified_by' => 'username',
                    ),
                ),
                'status' => array(
                    'name' => 'Trex_Task_Model_Status',
                    'alias' => 'status',
                    'metadata' => 'Trex_Task_Metadata_Status',
                    'local' => 'id',
                    'foreign' => 'task_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'task_id',
                        'status' => 'status',
                        'date_modified' => 'date_created',
                        'status_description' => 'description',
                        'created_by' => 'created_by',
                    ),
                    'set' => array(
                        'namespace' => 'Trex',
                        'module' => 'Task',
                        'controller' => 'Status',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'task',
                'order' => array(
                    array('date_created', 'DESC'),
                )
                
            ),
            'page' => array(
                'title' => 'LBL_MODULE_TASK',
            ),
            'shortcut' => array(
                array(
                    
                ),
            ),
        ));
        $this->field->set('operation.list.width', 160);
    }
}