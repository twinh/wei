<?php
/**
 * File
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
 * @version   2010-7-17 11:22:27
 * @since     2010-7-17 11:22:27
 * @todo      允许设置解码和编码的方法,过期时间
 */

/**
 * @see Qwin_Cache
 */
require_once 'Qwin/Cache.php';

class Qwin_Cache_File extends Qwin_Cache
{
    private $_path;

    public function connect($path)
    {
        if(file_exists($path))
        {
            // 转换并补全路径
            $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
            if(DIRECTORY_SEPARATOR != substr($this->_path, -1, 1))
            {
                $path .= DIRECTORY_SEPARATOR;
            }
            $this->_path = $path;
            return true;
        }
        return false;
    }

    public function set($name, $var = NULL, $expireTime = 0)
    {
        $filePath = $this->_path . $name . '.php';
        $var = '<?php return \'' . serialize($var) . '\';';
        file_put_contents($filePath, $var);
    }

    public function get($name)
    {
        $filePath = $this->_path . $name . '.php';
        if(file_exists($filePath))
        {
            return unserialize(require $filePath);
        }
        return NULL;
    }

    public function setExpireTime()
    {
        
    }
    
    public function getExpireTime()
    {
        
    }

    public function setEncodeType()
    {
        
    }

    public function getEncodeType()
    {
        
    }
}
