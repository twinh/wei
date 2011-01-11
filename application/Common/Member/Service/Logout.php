<?php
/**
 * Logout
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
 * @since       2010-10-12 14:04:48
 */

class Common_Member_Service_Logout extends Common_Service_BasicAction
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
            'checkLogin' => true,
        ),
        'view' => array(
            'display' => true,
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
        $metaHelper = Qwin::run('Qwin_Application_Metadata');
        $member     = $this->session->get('member');

        // 检查是否登陆
        if($config['data']['checkLogin'])
        {
            if(null === $member)
            {
                $url = $this->url->createUrl(array(
                    'module' => 'Member',
                    'controller' => 'Log',
                    'action' => 'Login'
                ));
                $return = array(
                    'result' => false,
                    'message' => $this->_lang->t('MSG_NOT_LOGIN'),
                    'method' => '',
                );
                if($config['view']['display'])
                {
                    $this->view->setRedirectView($return['message']);
                }
                return $return;
            }
        }

        // 清除登陆状态
        $this->session->set('member', null);

        // 跳转回上一页或默认首页
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?';
        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_LOGOUTED'),
            'url' => $url,
        );     
        if($config['view']['display'])
        {
            $this->view->setRedirectView($return['message'], $return['url']);
        }
        return $return;
    }
}
