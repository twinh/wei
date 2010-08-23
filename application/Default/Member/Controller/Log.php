<?php
/**
 * Log
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-5-23 0:22:37 utf-8 中文
 * @since     2010-5-23 0:22:37 utf-8 中文
 * @todo      标准化,一次取出该用户的全部资料,方便数据设置
 */

class Default_Member_Controller_Log extends Qwin_Trex_Controller
{
    public function actionLogin()
    {
        $ses = Qwin::run('-ses');
        $ini = Qwin::run('-ini');
        $gpc = Qwin::run('-gpc');
        $js = Qwin::run('Qwin_Helper_Js');
        $meta = &$this->__meta;

        $loginState = $ses->get('member');
        if(isset($loginState['login']) && true == $loginState['login'])
        {
            $js->show($this->t('MSG_LOGINED'));
        }

        if(!$_POST)
        {
            // 初始化视图变量数组
            $this->__view = array(
                'http_referer' => urlencode(Qwin::run('-str')->set($_SERVER['HTTP_REFERER']))
            );

            $this->loadView($ini->load('Resource/View/Layout/LoginPanel', false));$ini = Qwin::run('-ini');
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
