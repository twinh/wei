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
 * @version     $Id$
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
        'username' => 'guest',
    );
    
    protected $_data = array();
    
    public function __construct($source = null)
    {
        $data = (array)$this->session->get('user');
        if (empty($data) || !isset($data['username']) || !$data['username']) {
            
        } else {
            $this->_data = $data;
        }
    }
    
    public function call()
    {
        return $this;
    }
    
    /**
     * 注销登录
     * 
     * @return Qwin_User 
     */
    public function logout()
    {
        $this->_data = $this->_initData;
        return $this;
    }
    
    public function isLogin()
    {
        return 'guest' == $this->_data['username'];
    }
    
    /**
     * 析构方法,将用户信息保存到session中
     */
    public function __destruct()
    {
        $this->session->set('user', $this->_data);
    }
}