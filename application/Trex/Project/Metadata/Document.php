<?php
/**
 * Document
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
 * @subpackage  Project
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-11 10:24:37
 */

class Trex_Project_Metadata_Document extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'project_id' => array(
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
                        'isLink' => 1,
                    ),
                ),
                'title' => array(
                    'validator' => array(
                        'required',
                    ),
                ),
                'content' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => array(
                            'CKEditor',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            'model' => array(
                'project' => array(
                    'name' => 'Trex_Project_Model_Project',
                    'alias' => 'project',
                    'metadata' => 'Trex_Project_Metadata_Project',
                    'local' => 'project_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'project_id' => 'name',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'project_document',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_DOCUMENT',
            )
        ));
    }
}
