<?php
/**
 * Log
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
 * @since       2010-05-23 00:22:37
 */

class Member_Auth_Controller extends Controller_Widget
{
    public function __construct($config = array(), $module = null, $action = null)
    {
        parent::__construct($config, $module, $action);
        $this->_lang->appendByModule('member');
    }
    
    public function actionLogin()
    {
        // 提示已经登陆的信息
        $member = $this->getSession()->get('member');
        if ('guest' != $member['username']) {
            return $this->_view->alert($this->_lang['MSG_LOGINED']);
        }

        // 设置视图,加载登陆界面
        if (!$this->_request->isPost()) {
            $meta = $this->getMeta();
            $this->_view->assign(get_defined_vars());
        } else {
            /*
             * TODO 修复
             * return Com_Widget::getByModule('com/member/auth', 'login')->execute(array(
                'data' => $_POST,
            ));*/
            $dbData = Query_Widget::getByModule('member')
                    ->where('username = ? AND password = ?', array($this->_request['username'], md5($this->_request['password'])))
                    ->fetchOne();
            if (false === $dbData) {
                return $this->_view->alert($this->_lang['VLD_VALIDATEPASSWORD']);
            }
            
            $member = $dbData->toArray();
            unset($member['password']);
            $session = $this->getSession();
            $session->set('member',  $member);
            $session->set('style', $member['theme']);
            $session->set('language', $member['language']);
            
            $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?';
            return $this->_view->success($this->_lang['MSG_SUCCEEDED'], '?');
        }
    }

    public function actionLogout()
    {
        // TODO 修复
        //return Com_Widget::getByModule('com/member/auth', 'logout')->execute(array());
        // 清除登陆状态
        $session = $this->getSession();
        $session->set('member', null);

        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?';
        return $this->_view->success($this->_lang['MSG_LOGOUTED'], '?');
    }
}
