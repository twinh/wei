<?php
/**
 * Password
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2010-08-28 20:40:31
 */

class Com_Member_PasswordMeta extends Com_Meta
{
    public function setMeta()
    {
        $this->merge(array(
            // 基本属性
            'field' => array(
                'id' => array(
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'id',
                    ),
                ),
                'old_password' => array(
                    'form' => array(
                        '_type' => 'password',
                        'name' => 'old_password',
                    ),
                    'attr' => array(
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
                    'form' => array(
                        '_type' => 'password',
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isDbField' => 1,
                        'isDbQuery' => 0,
                    ),
                    'sanitiser' => array(
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
                    'form' => array(
                        '_type' => 'password',
                        'name' => 'confirm_password',
                    ),
                    'attr' => array(
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'equalTo' => '#password',
                        ),
                    ),
                ),
            ),
            'group' => array(),
            'model' => array(),
            'db' => array(
                'table' => 'member',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
            ),
        ));
    }

    /**
     * 修改密码时,将原密码置空
     *
     * @return string 空字符串
     */
    public function sanitiseEditPassword()
    {
        return '';
    }

    public function validateOldPassword($value, $name, $data)
    {
        $result = Com_Meta::getQueryByModule('com/member')
            ->select('password')
            ->where('id = ?', $data['id'])
            ->fetchOne();
        if (md5($value) != $result['password']) {
            return false;
        }
        return true;
    }
}
