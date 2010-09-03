<?php
/**
 * Detail
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
 * @since       2010-05-13 11:13:55
 */

class Trex_Member_Metadata_Detail extends Trex_Metadata
{
    public function  __construct()
    {
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'id' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'member_id' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'nickname' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'sex' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'sex',
                        ),
                        'name' => 'sex',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'sex',
                        )
                    ),
                ),
                'reg_ip' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'reg_ip',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isReadonly' => 1,
                    ),
                    'converter' => array(
                        'db' => array(
                            array(
                                Qwin::run('Qwin_Hepler_Util'),
                                'getIp'
                            ),
                        )
                    )
                ),
                'qq' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'telephone' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'qq' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'mobile' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'birthday' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'postcode' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'theme' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => 'base',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'language' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => 'en',
                        'name' => 'language',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
                array(
                    // 模型类名
                    'name' => 'Trex_Member_Model_Detail',
                    'alias' => 'detail',
                    // Metadata 中包含模型字段,表名,关系的定义,
                    'metadata' => 'Trex_Member_Metadata_Detail',
                    'type' => 'hasOne',
                    'local' => 'id',
                    'foreign' => 'member_id',
                )
            ),
            'db' => array(
                'table' => 'member_detail',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER_DETAIL',
            ),
        ));
    }
}
