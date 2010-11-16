<?php
/**
 * Login
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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-12 11:53:55
 */

class Trex_Member_Service_Login extends Trex_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_config = array(
        'set' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(
            'db' => array(
                'username' => null,
                'password' => null,
                'captcha' => null,
            ),
            'checkIsLogin' => true,
            'checkCaptcha' => true,
            'checkQuestion' => false,
            'loginLog' => true,
        ),
        'callback' => array(
            'afterLoggedIn' => array(),
        ),
        'view' => array(
            'display' => true,
            'url' => null,
        ),
        'this' => null,
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_Trex_Metadata');
        $member     = $this->session->get('member');
        $meta       = $this->_meta;

        // 检查是否登陆,如果登陆,提示已登陆
        if($config['data']['checkIsLogin'])
        {
            if(isset($member['id']) && 'guest' != $member['id'])
            {
                $return = array(
                    'result' => false,
                    'message' => $this->_lang->t('MSG_LOGINED'),
                );
                $this->setRedirectView($return['message']);
                return $return;
            }
        }

        // 不对验证码做检查,即删除验证码元数据配置
        if(!$config['data']['checkCaptcha'])
        {
            unset($meta['field']['captcha']);
        }

        // 加载验证类,并进行验证
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');
        $validateResult = $metaHelper->validateArray($config['data']['db'], $meta, $config['this']);
        if(true !== $validateResult)
        {
            $message = $this->showValidateError($validateResult, $meta, $config['view']['display']);
            $return = array(
                'result' => false,
                'message' => $message,
            );
            return $return;
        }
        
        // 验证通过,设置登陆数据到session
        $member = $config['this']->member;
        $this->session->set('member',  $member);
        $this->session->set('style', $member['theme']);
        $this->session->set('language', $member['language']);

        // 已登陆
        $this->executeCallback('afterLoggedIn', $config);

        if($config['data']['loginLog'])
        {
            /**
             * @see Trex_Service_Insert $_config
             */
            $logConfig = array(
                'set' => array(
                    'namespace' => 'Trex',
                    'module' => 'Member',
                    'controller' => 'LoginLog',
                ),
                'data' => array(
                    'db' => array(
                        'member_id' => $member['id'],
                    ),
                ),
                'view' => array(
                    'display' => false,
                ),
            );
            $logResult = Qwin::run('Trex_Service_Insert')->process($logConfig);
            if(!$logResult['result'])
            {
                if($config['view']['display'])
                {
                    $this->setRedirectView($logResult['message']);
                }
                return $logResult;
            }
        }

        // 设置视图数据
        if($config['view']['url'])
        {
            $url = $config['view']['url'];
        } else {
            $url = '?';
        }
        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['display'])
        {
            $this->setRedirectView($return['message'], $url);
        }
        return $return;
    }
}
