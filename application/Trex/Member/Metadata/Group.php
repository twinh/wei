<?php
/**
 * Group
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
 * @version   2010-7-15 14:49:16
 * @since     2010-7-15 14:49:16
 */

class Trex_Member_Metadata_Group extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'name' => array(
                    'form' => array(
                        'name' => 'name',
                    ),
                ),
                'image_path' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree',
                            'ajaxUpload'
                        ),
                        'name' => 'image_path',
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
                'table' => 'member_group',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER_GROUP',
            ),
        ));
    }
}
