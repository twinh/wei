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

class Trex_Category_Metadata_Category extends Trex_Metadata
{
    public function  __construct()
    {
        parent::setCommonMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'parent_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Trex_Category_Controller_Category', 'getCategoryResource'),
                        ),
                        'name' => 'parent_id',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'name' => array(
                    'form' => array(
                        'name' => 'name',
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
                        'name' => 'sign',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'image' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'image_2' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image_2',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'order' => array(
                    'form' => array(
                        'name' => 'order',
                    ),
                ),
                'to_url' => array(
                    'form' => array(
                        'name' => 'to_url',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(

            ),
            'db' => array(
                'table' => 'category',
                'order' => array(
                    array('order', 'ASC'),
                    array('date_created', 'DESC'),
                ),
                'limit' => 100,
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_CATEGORY',
            ),
        ));
    }
}
