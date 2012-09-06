<?php
/**
 * Qwin Framework
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: User.php 1221 2012-03-06 14:56:29Z itwinh@gmail.com $
 */

/**
 * User
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-06 17:36:33
 */
class Qwin_User extends Qwin_ArrayWidget
{
    protected $_initData = array(
        'id' => '0',
        'username' => 'guest',
        'acl' => array(),
    );

    protected $_data = array();

    public function __construct(array $options = array())
    {
        $data = (array)$this->session->get('user');

        if (empty($data) || !isset($data['username']) || !$data['username']) {
            // todo $this->login('guest');
            $user = $this->query('user')
                ->from('User_Record u')
                ->leftJoin('u.groups g')
                ->leftJoin('g.acl a')
                ->where('username = ?', 'guest')
                ->fetchOne();

            if (!$user) {
                return json_encode(array(
                    'code' => -1,
                    'message' => 'User "guest" was not found',
                ));
            }

            $acl = array();
            foreach ($user['groups'] as $group) {
                $acl += $group['acl']['resources'];
            }
            $data = $user->toArray();
            $data['acl'] = $acl;

            // 读取游客?
            $this->_data = $data;
        } else {
            $this->_data = $data;
        }
    }

    public function __invoke()
    {
        return $this;
    }

    /**
     * todo
     *
     * @return type
     */
    public function login()
    {
        return false;
    }

    /**
     * 注销登录
     *
     * @return Qwin_User
     */
    public function logout()
    {
        $this->_data = array();//$this->_initData;
        return $this;
    }

    public function isLogin()
    {
        return 'guest' != strtolower($this->_data['username']);
    }

    /**
     * 析构方法,将用户信息保存到session中
     */
    public function __destruct()
    {
        $this->session->set('user', $this->_data);
    }

    public function can($resource)
    {
        $acl = $this->_data['acl'];

        // 拥有所有权限
        if (isset($acl['*'])) {
            return true;
        }

        if (isset($acl[$resource])) {
            return true;
        }

        if ('/' == $resource{0}) {
            // 存在操作
            if (false !== $pos = strpos($resource, '/', 1)) {
                $module = substr($resource, 0, $pos);
                if (isset($acl[$module])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isAdmin()
    {
        return '1' == $this->_data['id'];
    }
}
