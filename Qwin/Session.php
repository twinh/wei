<?php
/**
 * Session
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
 * @version   2010-4-18 11:50:10 utf-8 中文
 * @since     2010-4-18 11:50:10 utf-8 中文
 * @todo      session start
 */

class Qwin_Session
{
    /**
     * 当前的命名空间
     * @var string
     */
    private $_namespace;
    
    public function __construct($namespace = 'Default')
    {
        $this->setNamespace($namespace);
    }

    /**
     * 设置一个命名空间
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        if('' === $namespace)
        {
            /**
             * @see Qwin_Session_Exception
             */
            require 'Qwin/Session/Exception.php';
            throw new Qwin_Session_Exception('Session namespace should not be empty.');
        }
        //if(isset($_SESSION[$namespace]))
        //{
            /**
             * @see Qwin_Session_Exception
             */
            //require 'Qwin/Session/Exception.php';
            //throw new Qwin_Session_Exception('Session namespace have been setted.');
        //}
        $this->_namespace = $namespace;
    }

    /**
     * 在当前命名空间设置一个会话
     * @param string $name
     * @param mixed $value
     * @return object
     */
    public function set($name, $value)
    {
        $_SESSION[$this->_namespace][$name] = $value;
        return $this;
    }

    /**
     * 获取当前命名空间的一个会话
     * @param string $name
     * @return mixed 会话的值
     */
    public function get($name)
    {
        if(!isset($_SESSION[$this->_namespace][$name]))
        {
            return null;
        }
        return $_SESSION[$this->_namespace][$name];
    }

    /**
     * 切换命名空间,如果不存在,则设置一个新的命名空间
     * @param string $namesapce
     * @return object
     */
    public function changeNamespace($namesapce)
    {
        if(isset($_SESSION[$namesapce]))
        {
            $this->_namespace = $namesapce;
        } else {
            $this->setNamespace($namespace);
        }
        return $this;
    }

    /**
     * 销毁当前或指定命名空间
     * @param string $namespace
     */
    public function destroy($namespace = null)
    {
        if(null != $namespace)
        {
            unset($_SESSION[$namespace]);
        } else {
            unset($_SESSION[$this->_namespace]);
        }
        //session_destroy();
    }
}