<?php
/**
 * File
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
 * @subpackage  Cache
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-17 11:22:27
 * @todo        允许设置解码和编码的方法,过期时间
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

    public function set($name, $var = null, $expireTime = 0)
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
        return null;
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
