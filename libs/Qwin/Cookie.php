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
 * Cookie
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-02 00:45:14
 */
class Qwin_Cookie extends Qwin_Widget
{
    /**
     * 设置或获取Cookie
     * 
     * @param string $key 名称
     * @return mixed 
     */
    public function call($key)
    {
        $args = func_get_args();
        
        if (2 == count($args)) {
            return $this->set($key, $args[1]);
        } else {
            return $this->get($key);
        }
    }
    
    /**
     * 获取Cookie
     * 
     * @param string $key 名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($_COOKIE[$key]) ? @unserialize($_COOKIE[$key]) : $default;
    }
    
    /**
     * 设置Cookie
     * 
     * @param string $key 名称
     * @param mixed $value 值
     * @param int $expire 过期时间
     * @todo 更多选项
     */
    public function set($key, $value, $expire = 0)
    {
        $_COOKIE[$key] = serialize($value);
        setcookie($key, $_COOKIE[$key], $expire);
    }
    
    /**
     * 移除Cookie
     * 
     * @param string $key 名称 
     */
    public function remove($key)
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            setcookie($key, null, -1);
        }
    }
}