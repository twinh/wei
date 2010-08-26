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

class Trex_Link_Metadata_Link extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_value' => '',
                        'name' => 'category_id',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'link'
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'link'
                        ),
                    ),
                ),
                'name' => array(
                    'form' => array(
                        'name' => 'name',
                    ),
                ),
                'url' => array(
                    'form' => array(
                        '_value' => 'http://',
                        'name' => 'url',
                    ),
                ),
                'target' => array(
                    'form' => array(
                        '_value' => '_self',
                        'name' => 'target',
                    ),
                ),
                'img_url' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'img_url',
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
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'date_created' => array(
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'date_modified',
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'link',
                'order' => array(
                    array('date_created', 'DESC')
                )
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_LINK',
            )
         ));
    }
}
