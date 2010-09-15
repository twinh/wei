<?php
/**
 * ApplicationStructure
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
 * @since       2010-09-11 21:41:36
 */

class Trex_Management_Metadata_ApplicationStructure extends Trex_Metadata
{
    public function __construct()
    {
        //$this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'namespace' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'pathName' => true,
                        ),
                    ),
                ),
                /*'parent_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PARENT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Management',
                                'controller' => 'ApplicationStructure',
                            ),
                            NULL,
                            array('id', 'parent_id', 'name')
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'application-structure',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'application-structure',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'application-structure',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
                'name' => array(

                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                ),*/
            ),
            'model' => array(

            ),
            'db' => array(
                'table' => 'management_application_structure',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MANAGEMENT_APPLICATION_STRUCTURE',
            ),
        ));
    }
}
