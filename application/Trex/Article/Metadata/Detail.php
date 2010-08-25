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

class Trex_Article_Metadata_Detail extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setIdMetadata()
             ->parseMetadata(array(
            'field' => array(
                'article_id' => array(
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'article_id',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'content' => array(
                    'basic' => array(
                        'order' => 400,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                        'name' => 'content',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'meta' => array(
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'meta',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 1,
                        'isView' => 0,
                    ),
                ),
                'meta_keywords' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_META_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        'name' => 'meta_keywords',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                ),
                'meta_description' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_META_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        'name' => 'meta_description',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
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
