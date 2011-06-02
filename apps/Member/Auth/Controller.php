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

class Com_Member_Auth_Controller extends Com_Controller
{
    public function actionLogin()
    {
        // 提示已经登陆的信息
        $member = $this->getSession()->get('member');
        if ('guest' != $member['username']) {
            return $this->getView()->alert(Qwin::call('-lang')->t('MSG_LOGINED'));
        }

        // 设置视图,加载登陆界面
        if (!$this->_request->isPost()) {
            $meta = $this->getMeta();
            $this->getView()->assign(get_defined_vars());
        } else {
            return Com_Widget::getByModule('com/member/auth', 'login')->execute(array(
                'data' => $_POST,
            ));
        }
    }

    public function actionLogout()
    {
        return Com_Widget::getByModule('com/member/auth', 'logout')->execute(array());
    }
}
