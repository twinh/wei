<?php
/**
 * Member
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
 * @since       2010-05-13 10:19:22
 */

class Trex_Member_Metadata_Member extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setIdMetadata()
             ->setOperationMetadata()
             ->parseMetadata(array(
            // 基本属性
            'field' => array(
                'group_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Member',
                                'controller' => 'Group',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                        'name' => 'group_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Trex',
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
            // 以显示作为目的的模型
            'linkModel' => array(
                array(
                    'name' => 'Trex_Member_Model_Detail',
                ),
            ),
            'db' => array(
                'table' => 'member',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
            ),
        ));
    }
}
