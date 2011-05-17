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
 * @package     Com
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-13 10:19:22
 */

class Com_Member_Meta extends Com_Meta
{
    public function setMeta()
    {
//        $this->setCommonMeta()
//             ->merge(array(
//            'field' => array(
//                'group_id' => array(
//                    'form' => array(
//                        '_type' => 'select',
//                        '_resourceGetter' => array(
//                            array('Com_Category_Widget', 'get'),
//                            array(
//                                'com/member/group',
//                                null,
//                                array('id', null, 'name'),
//                            ),
//                        ),
//                    ),
//                    'sanitiser' => array(
//                        'list' => array(
//                            array('Com_Category_Widget', 'sanitise'),
//                            array(
//                                'com/member/group',
//                                null,
//                                array('id', null, 'name'),
//                            ),
//                        ),
//                        'view' => 'list',
//                    ),
//                    'attr' => array(
//                        'isLink' => 1,
//                        'isList' => 1,
//                    ),
//                    'validator' => array(
//                        'rule' => array(
//                            'notNull' => true,
//                        ),
//                    ),
//                ),
//                'username' => array(
//                    'attr' => array(
//                        'isList' => 1,
//                        'isReadonly' => 1,
//                    ),
//                    'validator' => array(
//                        'rule' => array(
//                            'required' => true,
//                            'maxlength' => 40,
//                        ),
//                    ),
//                ),
//                'password' => array(
//                    'form' => array(
//                        '_type' => 'password',
//                    ),
//                    'attr' => array(
//                        'isReadonly' => 1,
//                        'isView' => 0,
//                    ),
//                    'sanitiser' => array(
//                        'db' => array(
//                            'md5'
//                        )
//                    ),
//                    'validator' => array(
//                        'rule' => array(
//                            'required' => true,
//                            'rangelength' => array(5, 40),
//                        ),
//                    ),
//                ),
//                'password2' => array(
//                    'form' => array(
//                        '_type' => 'password',
//                    ),
//                    'attr' => array(
//                        'isReadonly' => 1,
//                        'isView' => 0,
//                        'isDbField' => 0,
//                    ),
//                    'validator' => array(
//                        'rule' => array(
//                            'required' => true,
//                            'equalTo' => '#password',
//                        ),
//                    ),
//                ),
//                'email' => array(
//                    'attr' => array(
//                        'isList' => 1,
//                    ),
//                    'validator' => array(
//                        'rule' => array(
//                            'required' => true,
//                            'email' => true,
//                            'maxlength' => 256,
//                        ),
//                    ),
//                ),
//                'first_name' => array(
//
//                ),
//                'last_name' => array(
//
//                ),
//                'photo' => array(
//                    'form' => array(
//                        '_widget' => array(
//                            'fileTree',
//                            'ajaxUpload',
//                        ),
//                    ),
//                ),
//                'sex' => array(
//                    'form' => array(
//                        '_type' => 'select',
//                        '_resourceGetter' => array(
//                            array('Ide_Option_Widget', 'get'),
//                            'sex',
//                        ),
//                    ),
//                    'sanitiser' => array(
//                        'list' => array(
//                            array('Ide_Option_Widget', 'sanitise'),
//                            'sex',
//                        ),
//                        'view' => 'list',
//                    ),
//                    'attr' => array(
//                        'isLink' => 1,
//                    ),
//                ),
//                'birthday' => array(
//                    'form' => array(
//                        '_widget' => array(
//                            array(
//                                array('Datepicker_Widget', 'render')
//                            ),
//                        ),
//                    )
//                ),
//                'reg_ip' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//                'theme' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//                'language' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                    'form' => array(
//                        '_type' => 'select',
//                        '_resourceGetter' => array(
//                            array('Ide_Option_Widget', 'get'),
//                            'language',
//                        ),
//                    ),
//                ),
//                'telephone' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//                'mobile' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//                'homepage' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//                'address' => array(
//                    'basic' => array(
//                        'group' => 1,
//                    ),
//                ),
//            ),
//            'group' => array(
//                0 => 'LBL_GROUP_BASIC_DATA',
//                1 => 'LBL_GROUP_DETAIL_DATA',
//            ),
//            'model' => array(
//                /*'group' => array(
//                    'alias' => 'group',
//                    'local' => 'group_id',
//                    'foreign' => 'id',
//                    'type' => 'view',
//                    'fieldMap' => array(
//                        'group_id' => 'name',
//                    ),
//                    'asc' => array(
//                        'package' => 'Common',
//                        'module' => 'Member',
//                        'controller' => 'Group',
//                    ),
//                ),*/
//            ),
//            'db' => array(
//                'table' => 'member',
//                'nameField' => array(
//                    'username',
//                ),
//            ),
//            // 页面显示
//            'page' => array(
//                'title' => 'LBL_MODULE_MEMBER',
//                'icon' => 'user',
//                'tableLayout' => 2,
//            ),
//        ));
    }

    public function sanitiseListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this['db']['id'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-widget')->get('Lang');
        $module = $this->getModule();
        $html = Qwin_Util_JQuery::icon($url->url($module->toUrl(), 'editpassword', array($primaryKey => $copyData[$primaryKey])), $lang->t('ACT_EDIT_PASSWORD'), 'ui-icon-key')
              . parent::sanitiseListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function validateUsername($value, $name, $data)
    {
        if ('add' != Qwin::config('action')) {
            return true;
        }

        return true === $this->isUsernameExists($value) ? false : true;
    }

    public function validateEmail($value, $name, $data)
    {
        $result = Com_Meta::getQueryByModule('com/member')
            ->select('id')
            ->where('email = ? AND username <> ?', array($value, $data['username']))
            ->fetchOne();
        return false == $result ? true : false;
    }

    public function isUsernameExists($username)
    {
        $result = Com_Meta::getQueryByModule('com/member')
            ->select('id')
            ->where('username = ?', $username)
            ->fetchOne();
        if (false != $result) {
            $result = true;
        }
        return $result;
    }
}
