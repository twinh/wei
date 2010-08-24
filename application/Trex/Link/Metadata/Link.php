<?php
/**
 * Link
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
 * @version   2010-6-17 9:54:00 utf-8 中文
 * @since     2010-6-17 9:54:00 utf-8 中文
 */

class Default_Link_Metadata_Link extends Qwin_Trex_Metadata
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
                        'order' => 5,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_value' => '',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isListLink' => false,
                        'isList' => true,
                        'isDbField' => true,
                        'isDbQuery ' => true,
                    ),
                ),
                'category_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CATEGORY_ID',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_value' => '',
                        'name' => 'category_id',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'link'
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'news'
                        ),
                    ),
                ),
                'name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAME',
                        'order' => 15,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'name',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'url' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_URL',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => 'http://',
                        'name' => 'url',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'target' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TARGET',
                        'order' => 22,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '_self',
                        'name' => 'target',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'img_url' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IMG_URL',
                        'order' => 25,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'img_url',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ORDER',
                        'descrip' => '',
                        'order' => 30,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        'name' => 'order',
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
                        'order' => 35,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 40,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
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
                        'order' => 45,
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
                'operation' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_OPERATION',
                        'descrip' => '',
                        'order' => 45,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => '',
                        'name' => 'operation',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(

            ),
            'db' => array(
                'table' => 'link',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC')
                )
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_LINK',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
