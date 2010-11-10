<?php
/**
 * Pack 将多个文件打包成一个
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
 * @subpackage  File
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-11-02 16:08:22
 */

class Qwin_File_Pack
{
    /**
     * 通过Session的键名
     *
     * @var string/array
     */
    protected $_sessionKey = 'qwin_file_pack';

    protected $_fileList = array();

    public function __construct()
    {
        if(!session_id())
        {
            session_start();
        }
    }

    protected function _decodeSessionKey($key)
    {

    }

    public function setName($name)
    {
        $this->_sessionKey = $name;
        unset($_SESSION[$name]);
        return $this;
    }

    public function add($file, $name = null)
    {
        if(null == $name)
        {
            $name = $this->_sessionKey;
        }

        //
        if(!isset($_SESSION[$name]))
        {
            $_SESSION[$name] = array();
        }

        //
        if(!in_array($file, $_SESSION[$name]))
        {
            $_SESSION[$name][] = $file;
        }
        return $this;
    }

    public function selectName($name)
    {
        $this->_sessionKey = $name;
        return $this;
    }

    public function pack($name, $type = null)
    {
        if(!isset($_SESSION[$name]))
        {
            return false;
        }

        // 文件内容
        $content = null;

        // TODO !!!
        if('text/css' != $type)
        {
            foreach($_SESSION[$name] as $file)
            {
                if(file_exists($file))
                {
                    $content .= file_get_contents($file);
                }
            }
        } else {
            foreach($_SESSION[$name] as $file)
            {
                if(file_exists($file))
                {
                    // TODO 其他类型的路径
                    $fullPath = dirname($file);
                    $pattern = "/url\((.+?)\)/i";
                    $replacement = 'url(' . $fullPath . '/$1)';
                    $tempContent = file_get_contents($file);
                    $tempContent = preg_replace($pattern, $replacement, $tempContent);
                    $content .= $tempContent;
                }
            }
        }

        return $content;
    }
}