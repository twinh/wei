<?php
/**
 * Menu
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
 * @version   2010-5-25 8:00:36 utf-8 中文
 * @since     2010-5-25 8:00:36 utf-8 中文
 */

class Trex_AdminMenu_Metadata_Menu extends Trex_Metadata
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

                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'AdminMenu',
                                'controller' => 'Menu',
                            ),
                            null,
                            array('id', 'category_id', 'title'),
                        ),
                        'name' => 'category_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'AdminMenu',
                                'controller' => 'Menu',
                            ),
                            NULL,
                            array('id', 'category_id', 'title'),
                        ),
                    ),
                ),
                'title' => array(
                    'form' => array(
                        'name' => 'title',
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'url' => array(
                    'form' => array(
                        'name' => 'url',
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
                            'maxlength' => 400,
                        ),
                    ),
                ),
                'target' => array(
                    'form' => array(
                        '_value' => '_self',
                        'name' => 'target',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'order' => array(
                    'form' => array(
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'admin_menu',
                'order' => array(
                    array('order', 'ASC')
                )
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_ADMIN_MENU',
            ),
        ));
    }
}
