<?php
/**
 * Project
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
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-6-12 17:12:14 utf-8 中文
 * @since     2010-6-12 17:12:14 utf-8 中文
 */

class Trex_Project_Metadata_Project extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'parent_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PARENT_PROJECT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Project',
                                'controller' => 'Project',
                            ),
                            NULL,
                            array('id', 'parent_id', 'name')
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
                'introducer' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'customer_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Customer',
                                'controller' => 'Customer',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'name' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 200,
                        ),
                    ),
                ),
                'code' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'money' => array(
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'project-status',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'project-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'project-status',
                        ),
                    ),
                ),
                'start_time' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                ),
                'planed_end_time' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                ),
                'end_time' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'delay_reason' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
                array(
                    'name' => 'Trex_Project_Model_Project',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Project',
                    'local' => 'parent_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'parent_id' => 'name',
                    ),
                ),
                array(
                    'name' => 'Trex_Project_Model_Status',
                    'alias' => 'status',
                    'metadata' => 'Trex_Project_Metadata_Status',
                    'local' => 'id',
                    'foreign' => 'project_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'project_id',
                        'status' => 'status',
                        'date_modified' => 'date_created',
                    ),
                    'set' => array(
                        'namespace' => 'Trex',
                        'module' => 'Project',
                        'controller' => 'Status',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'project',
                'order' => array(
                    array('start_time', 'DESC'),
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT',
            ),
            'shortcut' => array(
            )
        ));
        $this->field->set('operation.list.width', 200);
        $this->field->set('date_created.attr.isList', 0);
        $this->field->set('date_modified.attr.isList', 0);
    }
}
