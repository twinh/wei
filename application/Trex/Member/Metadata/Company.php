<?php
/**
 * Company
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-20 07:58:07
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
                        'isLink' => 1,
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
