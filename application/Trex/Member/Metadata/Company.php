<?php
/**
 * Company
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
 * @version   2010-7-20 7:58:07
 * @since     2010-7-20 7:58:07
 */

class Trex_Member_Metadata_Company extends Qwin_Trex_Metadata
{
    public function  __construct()
    {
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 400,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'member_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 420,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'member_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'company' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_COMPANY',
                        'descrip' => '',
                        'order' => 440,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'company',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                        'order' => 460,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'customer_name',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                        'order' => 480,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'area',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                'department' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DEPARTMENT',
                        'descrip' => '',
                        'order' => 500,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                        'order' => 520,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                'telephone' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TELEPHONE',
                        'descrip' => '',
                        'order' => 540,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                'fax' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_FAX',
                        'descrip' => '',
                        'order' => 560,
                        'group' => 'LBL_GROUP_COMPANY_DATA',
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
            ),
            // 表之间的联系
            'model' => array(
            ),
            'db' => array(
                'table' => 'member_company',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        ));
    }
}
