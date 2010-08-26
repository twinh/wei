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

        /*$loginState = $ses->get('member');
        if(isset($loginState['login']) && true == $loginState['login'])
        {
            $js->show($this->t('MSG_LOGINED'));
        }*/

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
            // 加载关联模型,元数据
            $this->meta->loadRelatedData($meta['model']);
            // 获取模型类名称
            $modelName = $ini->getClassName('Model', $this->_set);
            $query = $this->meta->connectModel($modelName, $meta['model']);
            // POST 操作下,设置action为db
            $this->setAction('db');

            // 首先检查验证码
            if($ses->get('captcha') != $_POST['captcha'])
            {
                $msg = $this->t('MSG_ERROR_FIELD') . $this->t('LBL_FIELD_CAPTCHA') . '\n' . $this->t('MSG_ERROR_MSG') . $this->t('MSG_ERROR_CAPTCHA');
                $js->show($msg);
            }
            /**
             * 连接元数据
             * 转换数据
             * 验证数据
             */
            $this->meta->connetMetadata($meta);
            $data = $this->meta->convertSingleData($meta['field'], $this->_set['action'], $_POST);
            $this->meta->validateData($meta['field'], $data);
            
            $set = array(
                'namespace' => 'Default',
                'module' => 'Member',
                'controller' => 'Member',
            );
            $query = $this->meta->getQuery($set);
            $query = $query
                ->where('username = ? AND password = ?', array($data['username'], $data['password']))
                ->fetchOne();
            if(false == $query)
            {
                $js->show($this->t('MSG_ERROR_USERNAME_PASSWORD'));
            }
            $dbData = $query->toArray();
            $ses->set('member',  array(
                'login' => true,
                'username' => $data['username'],
                'id' => $dbData['id'],
            ));
            $ses->set('style', $dbData['detail']['theme_name']);
            $ses->set('lang', $dbData['detail']['lang']);

            $url = urldecode($gpc->p('_page'));
            if($url)
            {
                Qwin::run('-url')->to($url);
            } else {
                Qwin::run('-url')->to('?');
            }
        }
    }

    public function actionLogout()
    {
        $ses = Qwin::run('-ses');
        $js = Qwin::run('Qwin_Helper_Js');
        $loginState = $ses->get('member');
        if(isset($loginState['login']) && true == $loginState['login'])
        {
            $ses->set('member', array(
                'login' => false,
                'username' => null,
                'id' => null
            ));
        } else {
            $js->show($this->t('MSG_NOT_LOGIN'));
        }
        $url = Qwin::run('-str')->set($_SERVER['HTTP_REFERER']);
        if($url)
        {
            Qwin::run('-url')->to($url);
        } else {
            Qwin::run('-url')->to(url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'Login')));
        }
    }
}
