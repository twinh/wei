<?php
/**
 * Acl
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Acl
{
    // 初始化用户资料
    function __construct()
    {
        // 游客
        if(!isset($_SESSION['user']))
        {
            $_SESSION['user'] = array(
                'id' => 1,
                'group_id' => 1,
                'username' => 'guest',
                'nickname' => 'guest',
            );
        }
    }
    
    // 
    function getPermissions()
    {
        if(!isset($_SESSION['permissions']) || !isset($_SESSION['menu']))
        {
            qw('-qry')->setTable('admin_group');
            $query_arr = array(
                'WHERE' => "`id` = " . $_SESSION['user']['group_id'],
            );
            $sql = qw('-qry')->getOne($query_arr);
            $data = qw('-db')->getOne($sql);
            $_SESSION['permissions'] = unserialize($data['permissions']);
            $_SESSION['menu'] = unserialize($data['menu']);
        }
    }
    
    // 检查是否有权限
    function isPermit($set)
    {
        !isset($_SESSION['user']) && self::init();
        self::getPermissions();
        // namespace
        if(isset($_SESSION['permissions'][$set['namespace']]))
        {
            return true;
        }
        // namespace|controller
        if(isset($_SESSION['permissions'][$set['namespace'] . '|' . $set['controller']]))
        {
            return true;
        }
        // namespace|controller|action
        $key = implode('|', $set);
        if(isset($_SESSION['permissions'][$key]))
        {
            return true;
        }
        return false;
        
    }
    
    // 检查是否登录
    function isLogin()
    {
        if(!$_SESSION['user']['id'] || $_SESSION['user']['id'] == 1)
        {
            return false;
        }
        return true;
    }
    
    function setLogin($data)
    {
        unset($data['password'], $data['state_code']);
        unset($_SESSION['permissions']);
        $_SESSION['user'] = $data;
    }
    
    function setLogout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['permissions']);
    }
}