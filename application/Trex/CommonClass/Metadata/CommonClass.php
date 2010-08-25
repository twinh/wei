<?php
 /**
 * 通用分类
 *
 * 通用分类后台模型
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2009-11-21 15:21 utf-8 中文
 * @since     2009-11-21 15:21 utf-8 中文
 */

class Trex_CommonClass_Metadata_CommonClass extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata()
            ->parseMetadata(
            array(
                // 基本属性
                'field' => array(
                    'language' => array(
                        'form' => array(
                            'name' => 'language',
                        ),
                    ),
                    'sign' => array(
                        'form' => array(
                            'name' => 'sign',
                        ),
                    ),
                    'code' => array(
                        'form' => array(
                            '_type' => 'textarea',
                            'name' => 'code',
                        ),
                    ),
                ),
                'model' => array(),
                'db' => array(
                    'table' => 'common_class',
                    'primaryKey' => 'id',
                    'order' => array(
                        array('date_created', 'DESC'),
                    ),
                    'limit' => 20,
                ),
                // 页面显示
                'page' => array(
                    'title' => 'LBL_MODULE_COMMONCLASS',
                ),
                /*'type' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TYPE',
                        'descrip' => array(
                            '括号中表示<strong>代码</strong>的起始数字',
                            '<strong>代码</strong>域只读,由<strong>类型</strong>控制',
                        ),
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'radio',
                        '_typeExt' => 'common_class_auto_type',
                        '_value' => 1,
                        '_resource' => array(
                            1 => '系统(1)',
                            2 => '常用(2)',
                            3 => '项目(3)',
                            4 => '其他(4-9)'
                        ),
                        'name' => 'type',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                ),
                'code' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CODE',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'code',
                        'readonly' => 'readonly'
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'var_name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_VAR_NAME',
                        'order' => 25,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'var_name',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'title' => '顺序',
                        'descrip' => '',
                        'order' => 30,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '0',
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'value' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_VALUE',
                        'descrip' => array(
                            '<strong>名称</strong>可以自行添加多种语言',
                            '在 mapEditor 中,Key 指语言对应的代码,value 值对应的语言的值',
                        ),
                        'order' => 35,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => array(
                            'mapeditor' => array(
                                'data' => array(
                                    'en' => '',
                                    'zh-cn' => '',
                                ),
                            ),
                        ),
                        '_value' => '',
                        'name' => 'value',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),*/
         ));
    }
}
