<?php
/**
 * Material
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
 * @version   2010-7-19 13:09:02
 * @since     2010-7-19 13:09:02
 */

class Default_Product_Metadata_Material extends Qwin_Trex_Metadata
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
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAME',
                        'descrip' => '',
                        'order' => 5,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'name',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 80,
                        ),
                    ),
                ),
                'thumb' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_THUMB',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_icon' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'thumb',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DESCRIPTION',
                        'descrip' => '',
                        'order' => 22,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ORDER',
                        'descrip' => '',
                        'order' => 50,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 420,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_MODIFIED',
                        'descrip' => '',
                        'order' => 425,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_modified',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
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
                        'isUrlQuery' => 0,
                        'isList' => 1,
                        'isSqlField' => 0,
                        'isSqlQuery' => 0,
                        'isShow' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'product_material',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'where' => array(

                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_PRODUCT_MATERIAL',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
