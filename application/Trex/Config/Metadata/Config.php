<?php
/**
 * Config
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
 * @subpackage  Config
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-12-07 17:31:48
 */

class Trex_Config_Metadata_Config extends Trex_Metadata
{
    public function setMetadata()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'group_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Config',
                                'controller' => 'Group',
                            ),
                            '',
                            array('unique', null, 'name')
                        ),
                    ),
                ),
                'form_label' => array(
                ),
                'form_name' => array(
                    /*'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 32,
                        ),
                    ),*/
                ),
                'value' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'converter' => array(
                        'edit' => array(
                            array('Qwin_Converter_String', 'secureCode')
                        ),
                    ),
                ),
                'order' => array(

                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'form_type' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'form-type',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'form-type',
                        ),
                        'view' => 'list',
                    ),
                ),
                'form_resource' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'form_widget' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            'group' => array(
                1 => 'LBL_GROUP_FORM_SETTING',
            ),
            'model' => array(
                'group' => array(
                    'set' => array(
                        'namespace' => 'Trex',
                        'module' => 'Config',
                        'controller' => 'Group',
                    ),
                    'alias' => 'group',
                    'local' => 'group_id',
                    'foreign' => 'unique',
                    'type' => 'view',
                    'fieldMap' => array(
                        'group_id' => 'name',
                    ),
                ),
            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'config',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CONFIG',
            ),
        ));
    }

    /*public function convertEditValue($value, $name, $data, $copyData)
    {
        $this->field->set('value.form._type', $copyData['form_type']);
        // TODO
        if('CKEditor' == $copyData['form_widget'])
        {
            $this->field->set('value.form._widgetDetail', array(
                array(
                    array('Qwin_Widget_Editor_CKEditor', 'render'),
                ),
            ));
        }
        return $value;
    }*/
}

