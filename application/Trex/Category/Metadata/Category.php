<?php
/**
 * Category
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
 * @version   2010-7-12 16:52:28
 * @since     2010-7-12 16:52:28
 */

class Default_Category_Metadata_Category extends Default_Metadata
{
    public function  __construct()
    {
        parent::setMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'parent_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_value' => '',
                        '_resourceGetter' => array(
                            array('Default_Category_Controller_Category', 'getCategoryResource'),
                        ),
                        'name' => 'parent_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'name' => array(
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
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'sign' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'sign',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'image' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'image',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'image_2' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'image_2',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'order' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'to_url' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'to_url',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(

            ),
            'db' => array(
                'table' => 'category',
                'primaryKey' => 'id',
                'order' => array(
                    array('order', 'ASC'),
                    array('date_created', 'DESC'),
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_CATEGORY',
            ),
            'shortcut' => array(
            )
        ));
    }
}
