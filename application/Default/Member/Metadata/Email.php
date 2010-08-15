<?php
/**
 * Email
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
 * @version   2010-5-16 9:29:03 utf-8 中文
 * @since     2010-5-16 9:29:03 utf-8 中文
 */

class Default_Member_Metadata_Email extends Qwin_Trex_Metadata
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
                        'order' => 100,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
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
                'foreign_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_FOREIGN_ID',
                        'descrip' => '',
                        'order' => 105,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'foreign_id',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'email_address' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_ADDRESS',
                        'descrip' => '',
                        'order' => 110,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'email_address',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'email' => true,
                            'maxlength' => 400,
                        ),
                    ),
                ),
                'remark' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_REMARK',
                        'descrip' => '',
                        'order' => 115,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'remark',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
            ),
            'db' => array(
                'table' => 'email_address',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
                'rowNum' => 10,
            ),
        );
    }
}
