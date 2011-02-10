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
 * @package     Common
 * @subpackage  Config
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-12-07 17:31:48
 */

class Common_Config_Metadata_Config extends Common_Metadata
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
                                'namespace' => 'Common',
                                'module' => 'Config',
                                'controller' => 'Group',
                            ),
                            '',
                            array('form_name', null, 'title')
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                        'isLink' => 1,
                    ),
                ),
                'form_label' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'form_name' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                    /*'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 32,
                        ),
                    ),*/
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
                    'filter' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'filter'),
                            'yes-or-no',
                        ),
                        'view' => 'list',
                    ),
                ),
                'value' => array(
                    'filter' => array(
                        'edit' => array(
                            array('Qwin_filter_String', 'secureCode')
                        ),
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
                ),
                'form_type' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Common_Helper_Option', 'get'),
                            'form-type',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'filter' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'filter'),
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
                ),
                'form_widget' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                ),
            ),
            'group' => array(
                1 => 'LBL_GROUP_FORM_SETTING',
            ),
            'model' => array(
                'group' => array(
                    'asc' => array(
                        'namespace' => 'Common',
                        'module' => 'Config',
                        'controller' => 'Group',
                    ),
                    'alias' => 'group',
                    'local' => 'group_id',
                    'foreign' => 'form_name',
                    'type' => 'view',
                    'fieldMap' => array(
                        'group_id' => 'title',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'config',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CONFIG',
                'icon' => 'gear',
            ),
        ));
    }

    /*public function filterEditValue($value, $name, $data, $copyData)
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

