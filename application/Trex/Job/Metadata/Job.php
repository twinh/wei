<?php
/**
 * Enus
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
 * @subpackage  Job
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-18 10:32:28
 */

class Trex_Job_Metadata_Job extends Qwin_Trex_Metadata
{
    public function defaultMetadata()
    {
        return array(
            // 基本属性
            'field' => array(
                'id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 0,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'title' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TITLE',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'title',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 80,
                        ),
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_SHORT_DESCRIPTION',
                        'descrip' => '',
                        'order' => 40,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 400,
                        ),
                    ),
                ),
                'working_place' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_WORKING_PLACE',
                        'descrip' => '',
                        'order' => 60,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'working_place',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'number' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NUMBER',
                        'descrip' => '',
                        'order' => 80,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'number',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'responsibility' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_RESPONSIBILITY',
                        'descrip' => '',
                        'order' => 100,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => 'ckeditor',
                        'name' => 'responsibility',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'qualification' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_QUALIFICATION',
                        'descrip' => '',
                        'order' => 120,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => 'ckeditor',
                        'name' => 'qualification',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'enabled' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ENABLED',
                        'descrip' => '',
                        'order' => 140,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'enabled',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ORDER',
                        'descrip' => '',
                        'order' => 160,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 180,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_MODIFIED',
                        'descrip' => '',
                        'order' => 200,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_modified',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'operation' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_OPERATION',
                        'order' => 999,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => '',
                        'name' => 'operation',
                    ),
                    'attr' => array(
                        'isLink' => 0,
                        'isList' => 1,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                        'isView' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'job',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'where' => array(

                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_JOB',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
