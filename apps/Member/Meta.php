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

class Member_Meta extends Meta_Widget
{
    /*public function sanitiseListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this['db']['id'];
        $url = Qwin::widget('url');
        $lang = Qwin::call('-widget')->get('Lang');
        $module = $this->getModule();
        $html = Qwin_Util_JQuery::icon($url->url($module->getUrl(), 'editpassword', array($primaryKey => $copyData[$primaryKey])), $lang->t('ACT_EDIT_PASSWORD'), 'ui-icon-key')
              . parent::sanitiseListOperation($value, $name, $data, $copyData);
        return $html;
    }*/

    public function validateUsername($value, $name, $data)
    {
        return true === $this->isUsernameExists($value) ? false : true;
    }

    public function validateEmail($value, $name, $data)
    {
        // TODO 如何合理的获取当前模块名称等基本信息
        $result = Query_Widget::getByModule('member')
            ->select('id')
            ->where('email = ? AND id <> ?', array($value, $data['id']))
            ->fetchOne();
        return false == $result ? true : false;
    }

    public function isUsernameExists($username)
    {
        $result = Query_Widget::getByModule('member')
            ->select('id')
            ->where('username = ?', $username)
            ->fetchOne();
        if (false != $result) {
            $result = true;
        }
        return $result;
    }
    
    public function sanitiseList()
    {
        return call_user_func_array(array($this, 'setLink'), func_get_args());
    }
    
    public function sanitiseEditPassword()
    {
        return '';
    }
    
    public function validatePassword($value)
    {
        // md5('')
        return 'd41d8cd98f00b204e9800998ecf8427e' !== $value;
    }
}
