<?php
/**
 * Group
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
 * @since       2010-12-07 17:05:49
 */

class Common_Config_Metadata_Group extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                /*'parent_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PARENT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Common',
                                'module' => 'Config',
                                'controller' => 'Group',
                            ),
                            null,
                            array('form_name', null, 'title')
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                ),*/
                'title' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'form_name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'is_enabled' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_value' => 1,
                        '_resourceGetter' => array(
                            array('Common_Helper_Option', 'get'),
                            'yes-or-no',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'convert'),
                            'yes-or-no',
                        ),
                        'view' => 'list',
                    ),
                ),
                'order' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(

            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'config_group',
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CONFIG_GROUP'
            ),
        ));
    }
}
