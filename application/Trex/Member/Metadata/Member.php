<?php
/**
 * Member
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
 * @version   2010-5-13 10:19:22 utf-8 中文
 * @since     2010-5-13 10:19:22 utf-8 中文
 */

class Trex_Member_Metadata_Member extends Trex_Metadata
{
    public function  __construct()
    {
        parent::setIdMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'group_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Member',
                                'controller' => 'Group',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                        'name' => 'group_id',
                    ),
                    'converter' => array(
                        'attr' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Member',
                                'controller' => 'Group',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                    ),
                ),
                'username' => array(
                    'form' => array(
                        'name' => 'username',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'password' => array(
                    'form' => array(
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isReadonly' => 1,
                        'isView' => 0,
                    ),
                    'converter' => array(
                        'db' => array(
                            'md5'
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'email' => array(
                    'form' => array(
                        'name' => 'email',
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                            'maxlength' => 400,
                        ),
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
                        'name' => 'operation',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                        'isView' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
                array(
                    // 模型类名
                    'name' => 'Trex_Member_Model_Detail',
                    'asName' => 'detail',
                    // Metadata 中包含模型字段,表名,关系的定义,
                    'metadata' => 'Trex_Member_Metadata_Detail',
                    'type' => 'hasOne',
                    'local' => 'id',
                    'foreign' => 'member_id',
                ),
                array(
                    // 模型类名
                    'name' => 'Trex_Member_Model_Company',
                    'asName' => 'company',
                    // Metadata 中包含模型字段,表名,关系的定义,
                    'metadata' => 'Trex_Member_Metadata_Company',
                    'type' => 'hasOne',
                    'local' => 'id',
                    'foreign' => 'member_id',
                ),
            ),
            'db' => array(
                'table' => 'member',
                'primaryKey' => 'id',
                'order' => array(
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
            ),
        ));
    }

    public function passwordMetadata()
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
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'old_password' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_OLD_PASSWORD',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'password',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'old_password',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'rangelength' => array(5, 40),
                        ),
                    ),
                ),
                'password' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NEW_PASSWORD',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'password',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 0,
                    ),
                    'converter' => array(
                        'db' => array('md5')
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'rangelength' => array(5, 40),
                        ),
                    ),
                ),
                'confirm_password' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CONFIRM_PASSWORD',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'password',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'confirm_password',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'equalTo' => '#password'
                        ),
                    ),
                ),
            ),
            'model' => array(),
            'db' => array(
                'table' => 'member',
                'primaryKey' => 'id',
                'order' => array(
                    //array('detail.reg_time', 'DESC')
                )
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
                'rowNum' => 10,
            ),
        );
    }
}
