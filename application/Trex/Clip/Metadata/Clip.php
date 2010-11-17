<?php
/**
 * Clip
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
 * @subpackage  Clip
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-02
 */

class Trex_Clip_Metadata_Clip extends Trex_Metadata
{
    public function setMetadata()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 36,
                        ),
                    ),
                ),
                'value' => array(
                    /*'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                    ),*/
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'converter' => array(
                        'edit' => array(
                            array('Qwin_Converter_String', 'secureCode')
                        ),
                    ),
                ),
                'description' => array(

                ),
                'form_type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'form-type',
                        ),
                    ),
                ),
                'form_widget' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
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
                'table' => 'clip',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CLIP',
            ),
        ));
    }

    public function convertEditValue($value, $name, $data, $copyData)
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
    }
}
