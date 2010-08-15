<?php
/**
 * Detail
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
 * @version   2010-06-07
 * @since     2010-06-07
 */

class Default_Product_Metadata_Detail extends Qwin_Trex_Metadata
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
                        'order' => 20,
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
                'product_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'product_id',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'content' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CONTENT',
                        'descrip' => '',
                        'order' => 50,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => 'ckeditor',
                        '_value' => '',
                        'name' => 'content',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'meta' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_META',
                        'descrip' => '',
                        'order' => 60,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'meta',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
            ),
            'model' => array(),
            'db' => array(
                'table' => 'product_detail',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_PRODUCT_DETAIL',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
