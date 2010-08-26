<?php
/**
 * Email
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
 * @since       2010-05-16 09:29:03
 */

class Trex_Member_Metadata_Email extends Qwin_Trex_Metadata
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
                        'order' => 100,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
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
                'foreign_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_FOREIGN_ID',
                        'descrip' => '',
                        'order' => 105,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'foreign_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'email_address' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_ADDRESS',
                        'descrip' => '',
                        'order' => 110,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'datepicker',
                        '_value' => '',
                        'name' => 'email_address',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'email' => true,
                            'maxlength' => 400,
                        ),
                    ),
                ),
                'remark' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_EMAIL_REMARK',
                        'descrip' => '',
                        'order' => 115,
                        'group' => 'LBL_GROUP_EMAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'remark',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
            ),
            'db' => array(
                'table' => 'email_address',
                'primaryKey' => 'id',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
                'rowNum' => 10,
            ),
        );
    }
}
