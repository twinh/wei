<?php
/**
 * Setting
 *
 * Copyright (c) 2009-2010 Twin Huang. All rights reserved.
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
 * @author    Twin Huang <Twin Huang>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-04-17 14:58:42
 * @since     2010-04-17 14:58:42 utf-8 中文
 */

class System_Module_Metadata_Module extends Qwin_Trex_Metadata
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
                        'order' => 0,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'namespace' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAMESPACE',
                        'descrip' => '',
                        'order' => 5,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'namespace',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAME',
                        'descrip' => '',
                        'order' => 10,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'name',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'code' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CODE',
                        'descrip' => '',
                        'order' => 15,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'code',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'author' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_AUTHOR',
                        'descrip' => '',
                        'order' => 20,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'author',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'version' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_VERSION',
                        'descrip' => '',
                        'order' => 25,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'version',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'license' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_LICENSE',
                        'descrip' => '',
                        'order' => 30,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'license',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'public_date' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PUBLIC_DATE',
                        'descrip' => '',
                        'order' => 35,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'public_date',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'install_date' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_INSTALL_DATE',
                        'descrip' => '',
                        'order' => 40,
                        'group' => '',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'install_date',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DESCRIPTION',
                        'descrip' => '',
                        'order' => 45,
                        'group' => '',
                    ),
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
            // 附加属性
            'fieldExt' => array(
                'key' => 'id',
            ),
            // 核心
            'core' => array(
                'table' => 'system_module'
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
                'rowNum' => 10,
            ),
            'shortcut' => array(
                array(
                    'name' => '快速添加',
                    'link' => 'http://bbbb',
                )
            )
        );
    }
}
