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
        $ini = Qwin::run('-ini');
        $js = Qwin::run('Qwin_Helper_Js');
        $meta = $this->_meta;

        /**
         * 提示已经登陆的信息
         */
        $member = $this->_session->get('member');
        if('guest' != $member['username'])
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
            // 加载验证类
            Qwin::run('Qwin_Class_Extension')
                ->setNamespace('validator')
                ->addClass('Qwin_Validator_JQuery');
            // 验证
            $validateResult = $meta->validateArray($meta['field'], $_POST, $this);
            if(true !== $validateResult)
            {
                $message = $this->_lang->t('MSG_ERROR_FIELD')
                    . $this->_lang->t($meta['field'][$validateResult->field]['basic']['title'])
                    . '\r\n'
                    . $this->_lang->t('MSG_ERROR_MSG')
                    . $meta->format($this->_lang->t($validateResult->message), $validateResult->param);
                $js->show($message);
            }

            /**
             * 验证通过,设置登陆数据到session
             */
            $member = $this->_member;
            $this->_session->set('member',  $member);
            $this->_session->set('style', $member['theme']);
            $this->_session->set('language', $member['language']);

            /**
             * 增加登陆记录
             */
            $logQuery = $meta->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Member',
                'controller' => 'LoginLog',
            ));
            $logQuery = new Trex_Member_Model_LoginLog();
            $logQuery['id'] = Qwin::run('Qwin_converter_String')->getUuid();
            $logQuery['member_id'] = $member['id'];
            $logQuery['ip'] = Qwin_Helper_Util::getIp();
            $logQuery['date_created'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
            $logQuery->save();

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
        $js = Qwin::run('Qwin_Helper_Js');

        /**
         * 提示未登陆的信息
         */
        if(null === $this->_session->get('member'))
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
        $this->_session->set('member', null);

        /**
         * 跳转回上一页或默认首页
         */
        !isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] = '?';
        return $this->setView('alert', $this->_lang->t('MSG_LOGOUTED'), $_SERVER['HTTP_REFERER']);
    }

    public function validateCaptcha($value, $name, $data)
    {
        if($value == $this->_session->get('captcha'))
        {
            return true;
        }
        return new Qwin_Validator_Result(false, $name, 'MSG_ERROR_CAPTCHA');
    }

    public function validatePassword($value, $name, $data)
    {
        $value = md5($value);
        $result = $this
            ->_meta
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Member',
                'controller' => 'Member',
            ))
            ->where('username = ? AND password = ?', array($data['username'], $value))
            ->fetchOne();
        if(false != $result)
        {
            $this->_member = $result->toArray();
            return true;
        }
        $this->_session->set('member', null);
        return new Qwin_Validator_Result(false, 'password', 'MSG_ERROR_USERNAME_PASSWORD');
    }
}
