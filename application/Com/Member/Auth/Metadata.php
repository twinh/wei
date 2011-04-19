<?php
/**
 * LoginLog
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
 * @package     QWIN_PATH
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-13 11:20:21
 */

class Com_Member_Auth_Metadata extends Com_Metadata
{

    public function setMetadata()
    {
        $this->merge(array(
            'field' => array(
                'captcha' => array(
                    'form' => array(
                        'class' => 'ui-widget-content ui-corner-all',
                        'maxlength' => 4,
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'username' => array(
                    'form' => array(
                        'class' => 'ui-widget-content ui-corner-all',
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
                'password' => array(
                    'form' => array(
                        '_type' => 'password',
                        'class' => 'ui-widget-content ui-corner-all',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'sanitiser' => array(
                        'db' => array('md5')
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(

            ),
            'db' => array(
                'table' => 'member',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
            ),
        ));
    }

    public function validateCaptcha($value, $name, $data)
    {
        if ($value == Qwin::call('-session')->get('captcha')) {
            return true;
        }
        // MSG_ERROR_CAPTCHA
        return false;
    }

    public function validatePassword($value, $name, $data)
    {
        $value = md5(trim($value));
        $query = Com_Metadata::getQueryByModule('com/member', array(
            'type' => 'db',
        ));
        $result = $query->where('username = ? AND password = ?', array($data['username'], $value))
                ->fetchOne();

        if (false != $result) {
            $member = $result->toArray();
            unset($member['password']);
            // 加入到元数据中,方便调用
            $this->member = $member;
            return true;
        }
        Qwin::call('-session')->set('member', null);
        return false;
    }
}
