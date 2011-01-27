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
 * @package     Common
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-23 00:22:37
 */

class Common_Member_Controller_Log extends Common_Controller
{
    public function actionLogin()
    {
        $js = Qwin::run('Qwin_Helper_Js');
        $meta = $this->_meta;

        // 提示已经登陆的信息
        /*$member = $this->session->get('member');
        if ('guest' != $member['username']) {
            return $this->view->setRedirectView($this->_lang->t('MSG_LOGINED'));
        }*/

        // 设置视图,加载登陆界面
        if(empty($_POST))
        {
            $this->view->assign(get_defined_vars());
        } else {
            $service = new Common_Member_Service_Login();
            $service->process(array(
                'asc' => $this->_asc,
                'data' => array(
                    'db' => $_POST,
                ),
            ));
        }
    }

    public function actionLogout()
    {
        return Qwin::run('Common_Member_Service_Logout')->process(array(
            'asc' => $this->_asc,
            'this' => $this,
        ));
    }
}
