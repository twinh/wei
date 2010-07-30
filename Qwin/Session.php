<?php
/**
 * Session
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
 * @subpackage  Session
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-4-18 11:50:10
 * @todo        session start
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
