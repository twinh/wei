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

class Default_Project_Metadata_Project extends Qwin_Trex_Metadata
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
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAME',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'name',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 200,
                        ),
                    ),
                ),
                'code' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CODE',
                        'descrip' => '',
                        'order' => 13,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'code',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'start_time' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_START_TIME',
                        'descrip' => '',
                        'order' => 15,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'start_time',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'planed_end_time' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PLANED_END_TIME',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'planed_end_time',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'end_time' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_END_TIME',
                        'descrip' => '',
                        'order' => 25,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'end_time',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DESCRIPTION',
                        'descrip' => '',
                        'order' => 25,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_value' => '',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
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
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                        'isView' => 0,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 30,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
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
                        'order' => 35,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_modified',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'project',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
