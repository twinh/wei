<?php
/**
 * CustomerFeedback
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
 * @version   2010-7-18 13:59:21
 * @since     2010-7-18 13:59:21
 */

class Default_CustomerFeedback_Metadata_CustomerFeedback extends Qwin_Trex_Metadata
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
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'company' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_COMPANY',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'company',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 200,
                        ),
                    ),
                ),
                'customer_name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CUSTOMER_NAME',
                        'descrip' => '',
                        'order' => 40,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'customer_name',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'area' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_AREA',
                        'descrip' => '',
                        'order' => 60,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'area',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'department' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DEPARTMENT',
                        'descrip' => '',
                        'order' => 80,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'department',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'position' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_POSITION',
                        'descrip' => '',
                        'order' => 100,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'position',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'telephone' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TELEPHONE',
                        'descrip' => '',
                        'order' => 120,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'telephone',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'email' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL',
                        'descrip' => '',
                        'order' => 140,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'email',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                            'maxlength' => 200,
                        ),
                    ),
                ),
                'fax' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_FAX',
                        'descrip' => '',
                        'order' => 180,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'fax',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'interested_product' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_INTERESTED_PRODUCT',
                        'descrip' => '',
                        'order' => 200,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'checkbox',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'product',
                        ),
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'interested_product',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'implode'),
                            '|',
                        ),
                        'edit' => array(
                            array('Qwin_converter_String', 'explode'),
                            '|',
                        )
                    ),
                ),
                'ip' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IP',
                        'descrip' => '',
                        'order' => 220,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'ip',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                        'isReadonly' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array(
                                Qwin::run('Qwin_Hepler_Util'),
                                'getIp'
                            ),
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'message' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_MESSAGE',
                        'descrip' => '',
                        'order' => 240,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'message',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array('Qwin_converter_String', 'secureCode')
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 65535,
                        ),
                    ),
                ),
                'is_processed' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IS_PROCESSED',
                        'descrip' => '',
                        'order' => 250,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'is_processed',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 300,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'operation' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_OPERATION',
                        'order' => 999,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => '',
                        'name' => 'operation',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isSqlField' => 0,
                        'isSqlQuery' => 0,
                        'isShow' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'customer_feedback',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'where' => array(

                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_CUSTOMER_FEEDBACK',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
