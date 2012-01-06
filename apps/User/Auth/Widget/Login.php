<?php
/**
 * Login
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
 * @package     QWIN_PATH
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-12 11:53:55
 */

class Com_Member_Auth_Widget_Login extends Qwin_Widget
{
    /**
     * 服务的基本配置
     * @var array
     */
    public $options = array(
        'data' => array(
            'username' => null,
            'password' => null,
            'captcha' => null,
        ),
        'checkIsLogin' => true,
        'checkCaptcha' => false,
        'checkQuestion' => false,
        'loginLog' => true,
        'display' => true,
    );

    public function render($options)
    {
        $options = $options + $this->_options;
        $session = Qwin::call('-session');
        $member = $session['member'];
        $meta = Com_Meta::getByModule('com/member/auth');

        // 检查是否登陆,如果登陆,提示已登陆
        if ($options['checkIsLogin']) {
            if (isset($member['id']) && 'guest' != $member['id']) {
                $return = array(
                    'result' => false,
                    'message' => $this->_lang->t('MSG_LOGINED'),
                );
                if ($options['display']) {
                    $view = $this->_View;
                    $view->alert($return['message']);
                }
                return $return;
            }
        }

        // 不对验证码做检查,即删除验证码元数据配置
        if (!$options['checkCaptcha']) {
            unset($meta['fields']['captcha']);
        }

        /* @var $validator Validator_Widget */
        $validator = $this->_widget->get('Validator');
        if (!$validator->valid($options['data'], $meta)) {
            $return = array(
                'result' => false,
                'message' => $validator->getInvalidMessage(),
            );
            if ($options['display']) {
                return $this->_View->alert($return['message']['title'], null, $return['message']['content']);
            }
            return $return;
        }
        
        // 验证通过,设置登陆数据到session
        $member = $meta->member;
        $session->set('member',  $member);
        $session->set('style', $member['theme']);
        $session->set('language', $member['language']);

        if ($options['loginLog']) {
            $logResult = Qwin::call('-widget')->get('Add')->execute(array(
                'module'    => 'com/member/log',
                'data'      => array(
                    'member_id' => $member['id'],
                ),
                'display'   => false,
            ));
            if (false == $logResult['result']) {
                if ($options['display']) {
                    $view = $this->_View;
                    $view->alert($logResult['message']);
                }
                return $logResult;
            }
        }

        // 设置视图数据
        if (isset($options['data']['_page'])) {
            $url = urldecode($options['data']['_page']);
        } else {
            $url = '?';
        }
        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_SUCCEEDED'),
            'url' => $url,
        );
        if ($options['display']) {
            $this->_View->success($return['message'], $url);
        }
        return $return;
    }
}
