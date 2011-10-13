<?php
/**
 * Logout
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2010-10-12 14:04:48
 */

class Com_Member_Auth_Widget_Logout extends Qwin_Widget
{
    /**
     * 服务的基本配置
     * @var array
     */
    public $options = array(
        'checkLogin' => true,
        'display' => true,
    );

    public function execute($options)
    {
        $options = $options + $this->_options;
        $session = Qwin::call('-session');
        $member = $session['member'];
        $lang = Qwin::call('-lang');

        // 检查是否登陆
        if ($options['checkLogin']) {
            if (null === $member) {
                $return = array(
                    'result' => false,
                    'message' => $lang->t('MSG_NOT_LOGIN'),
                );
                if ($options['display']) {
                    $this->_View->alert($return['message']);
                }
                return $return;
            }
        }

        // 清除登陆状态
        $session->set('member', null);

        // 跳转回上一页或默认首页
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?';
        $return = array(
            'result' => true,
            'message' => $lang->t('MSG_LOGOUTED'),
            'url' => $url,
        );     
        if ($options['display']) {
            $this->_View->success($return['message'], $return['url']);
        }
        return $return;
    }
}
