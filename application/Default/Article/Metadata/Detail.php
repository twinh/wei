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
 * @version   2010-5-27 0:13:24 utf-8 中文
 * @since     2010-5-27 0:13:24 utf-8 中文
 */

class Default_Article_Metadata_Detail extends Default_Metadata
{
    public function  __construct()
    {
        $this->parseMetadata(array(
            'field' => array(
                'id' => array(
                    'basic' => array(
                        'order' => 20,
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                        'isView' => 0,
                    ),
                ),
                'article_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'article_id',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'content' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CONTENT',
                        'order' => 50,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                        'name' => 'content',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'meta' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_META',
                        'order' => 60,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'meta',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
            ),
            'model' => array(),
            'db' => array(
                'table' => 'article_detail',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_ARTICLE_DETAIL',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        ));
    }
}
