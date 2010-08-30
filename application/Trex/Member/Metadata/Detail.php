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
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 50,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
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
                        'order' => 55,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
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
                'nickname' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NICKNAME',
                        'descrip' => '',
                        'order' => 60,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        'name' => 'nickname',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'sex' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_SEX',
                        'descrip' => '',
                        'order' => 65,
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
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'sex',
                        )
                    ),
                ),
                'reg_time' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_REG_TIME',
                        'descrip' => '',
                        'order' => 70,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'reg_time',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'reg_ip' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_REG_IP',
                        'descrip' => '',
                        'order' => 75,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'reg_ip',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                'theme' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_THEME_NAME',
                        'descrip' => '',
                        'order' => 80,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => 'base',
                        'name' => 'theme',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'language' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_LANG',
                        'descrip' => '',
                        'order' => 85,
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => 'en',
                        'name' => 'language',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
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
                )
            ),
            'db' => array(
                'table' => 'member_detail',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER_DETAIL',
                'rowNum' => 10,
            ),
            'shortcut' => array(
                array(
                    'name' => '快速添加2',
                    'link' => 'http://bbbb',
                )
            )
        ));
    }
}
