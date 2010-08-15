<?php
/**
 * Log
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
 * @version   2010-5-24 7:52:43 utf-8 中文
 * @since     2010-5-24 7:52:43 utf-8 中文
 */

class Default_Member_Metadata_Log extends Qwin_Trex_Metadata
{
    public function defaultMetadata()
    {
        return array(
            // 基本属性
            'field' => array(
                'captcha' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CAPTCHA',
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'captcha',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 0,
                        'isSqlQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'username' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_USERNAME',
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'username',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'password' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PASSWORD',
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isUrlQuery' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                    'converter' => array(
                        'db' => array('md5')
                    )
                ),
            ),
            // 附加属性
            'fieldExt' => array(
                'key' => 'id',
            ),
            'model' => array(

            ),
            'db' => array(
                'table' => 'member',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
            ),
        );
    }
}
