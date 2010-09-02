<?php
/**
 * Status
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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-31 9:41:37
 */

class Trex_Project_Metadata_Status extends Trex_Metadata
{
    public function __construct()
    {
        $this->setIdMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'project_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PROJECT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
                'project_name' => array(
                    'form' => array(
                        '_type' => 'plain',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                        'isDbField' => 0,
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'project-status',
                        ),
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
                'date_created' => array(
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
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
            ),
            'db' => array(
                'table' => 'project_status',
                'order' => array(
                    array('date_created', 'ASC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_STATUS',
            ),
        ));
    }
}
