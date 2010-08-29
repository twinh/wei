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
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-23 00:22:37
 */

class Trex_Member_Controller_Log extends Trex_Controller
{
    public function actionLogin()
    {
        $ses = Qwin::run('-ses');
        $ini = Qwin::run('-ini');
        $gpc = Qwin::run('-gpc');
        $js = Qwin::run('Qwin_Helper_Js');
        $meta = $this->_meta;

        /**
         * 提示已经登陆的信息
         */
        if(null !== $ses->get('member'))
        {
            return $this->setRedirectView($this->_lang->t('MSG_LOGINED'));
        }

        if(empty($_POST))
        {
            /**
             * 设置视图,加载登陆界面
             */
            $this->_view = array(
                'class' => 'Trex_Member_View_Login',
                'data' => get_defined_vars(),
            );
        } else {
            /**
             * 检查验证码
             */
            if($ses->get('captcha') != $this->_request->p('captcha'))
            {
                $message = $this->_lang->t('MSG_ERROR_FIELD') . $this->_lang->t('LBL_FIELD_CAPTCHA') . '\n' . $this->_lang->t('MSG_ERROR_MSG') . $this->_lang->t('MSG_ERROR_CAPTCHA');
                $js->show($message);
            }

            /**
             * 转换,检验其他数据
             */
            $data = $meta->convertSingleData($meta['field'], $meta['field'], 'db', $_POST);
            $meta->validateData($meta['field'], $data);

            /**
             * 从数据库中查询数据,建议是否存在此用户
             */
            $set = array(
                'namespace' => 'Trex',
                'module' => 'Member',
                'controller' => 'Member',
            );
            $query = $meta->getDoctrineQuery($set);
            $result = $query
                ->where('username = ? AND password = ?', array($data['username'], $data['password']))
                ->fetchOne();
            if(false == $result)
            {
                $ses->set('member', null);
                $js->show($this->_lang->t('MSG_ERROR_USERNAME_PASSWORD'));
            }
            
            /**
             * 验证通过,设置登陆数据到session
             */
            $member = $result->toArray();
            $ses->set('member',  $member);
            $ses->set('style', $member['detail']['theme']);
            $ses->set('language', $member['detail']['language']);

            /**
             * 跳转到上一页或默认首页
             */
            $url = urldecode($this->_request->p('_page'));
            '' == $url && $url = '?';
            $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    public function actionLogout()
    {
        $ses = Qwin::run('-ses');
        $js = Qwin::run('Qwin_Helper_Js');

        /**
         * 提示未登陆的信息
         */
        if(null === $ses->get('member'))
        {
            $url = Qwin::run('Qwin_Url')->createUrl(array(
                'module' => 'Member',
                'controller' => 'Log',
                'action' => 'Login'
            ));
            $js->show($this->_lang->t('MSG_NOT_LOGIN'), $url);
        }

        /**
         * 清除登陆状态
         */
        $ses->set('member', null);

        /**
         * 跳转回上一页或默认首页
         */
        !isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] = '?';
        return $this->setView('alert', $this->_lang->t('MSG_LOGOUTED'), $_SERVER['HTTP_REFERER']);
    }
}
