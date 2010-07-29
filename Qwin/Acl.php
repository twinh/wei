<?php
/**
 * acl 的名称
 *
 * acl 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2009-10-31 01:19:05 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 */

// utf-8 编码
// 0910
// Access control list 
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
?>
