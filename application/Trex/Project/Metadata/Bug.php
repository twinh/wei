<?php
/**
 * Bug
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
 * @version   2010-7-9 14:58:27
 * @since     2010-7-9 14:58:27
 */

class Trex_Project_Metadata_Bug extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'project_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PROJECT_NAME'
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
                ),
                'title' => array(

                ),
                'priority' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                    ),
                ),
                'severity' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                    ),
                ),
                'reproducibility' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'level-status',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'level-status',
                        ),
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'bug-status',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'bug-status',
                        ),
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
                'created_by' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CREATOR'
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'modified_by' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_MODIFIER'
                    ),
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
            ),
            'model' => array(
                array(
                    'name' => 'Trex_Project_Model_Project',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Project',
                    'type' => 'hasOne',
                    'local' => 'project_id',
                    'foreign' => 'id',
                    'aim' => 'view',
                    'viewMap' => array(
                        'project_id' => 'name',
                    ),
                ),
                array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'member',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'created_by',
                    'foreign' => 'id',
                    'aim' => 'view',
                    'viewMap' => array(
                        'created_by' => 'username',
                    ),
                ),
                array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'member2',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'modified_by',
                    'foreign' => 'id',
                    'aim' => 'view',
                    'viewMap' => array(
                        'modified_by' => 'username',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'project_bug',
                'order' => array(
                    array('date_created', 'DESC')
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_BUG',
            ),
        ));
    }
}
