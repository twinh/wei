<?php
/**
 * Cache
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
 * @since       2010-3-12 15:44:19
 * $todo        允许自定义缓存引擎
 */

class Qwin_Cache
{
    /**
     * 过期时间,0表示不过期
     * @var int
     */
    private $_expireTime = 0;

    /**
     * 错误代码
     * @var int
     */
    private $_errorCode = 0;

    /**
     * 缓存的引擎,引擎即为使用的缓存类最后的名称
     * @var string
     */
    private $_cacheEngine;

    /**
     * 连接引擎
     */
    public function connect($argv)
    {
        return false;
    }

    /**
     * 设置缓存
     * @param string $name 缓存标志
     * @param mixed $var 缓存内容
     * @param int $expireTime 过期时间
     * @return bool 是否设置成功
     */
    public function set($name, $var = null, $expireTime = 0)
    {
        $this->_expireTime = $expireTime;
        $this->_errorCode = -1;
        return false;
    }

    /**
     * 获取缓存数据
     * @param string 缓存标志
     * @return 
     */
    public function get($name)
    {
        return null;
    }

    public function isExpire($name)
    {
        return true;
    }

    /**
     * 设置缓存引擎
     * @param string $engine
     * @return bool
     */
    public final function setCacheEngine($engine)
    {
        if($this->isEngineExists($engine))
        {
            $this->_cacheEngine = $engine;
            return true;
        }
        return false;
    }

    /**
     * 获取当前缓存引擎的名称
     * @return 缓存引擎的名称
     */
    public final function getCacheEngine()
    {
        return $this->_cacheEngine;
    }

    /**
     * 检查缓存引擎是否存在
     * @param string $engine 引擎名称
     * @return bool 是否存在
     */
    public final function isEngineExists($engine)
    {
        /**
         * 如果类管理器存在,通过类管理器加载类来判断引擎类是否存在
         */
        if(class_exists('Qwin_Class'))
        {
            /**
             * @todo 不存在时,继续判断
             */
            return Qwin::load('Qwin_Cache_' . $engine);
        }
        if(file_exists('Qwin/Cache/' . $engine . '.php'))
        {
            require_once 'Qwin/Cache/' . $engine . '.php';
        }
        if(class_exists('Qwin_Cache_' . $engine))
        {
            return true;
        }
        return false;
    }

    function writeArr($arr, $path, $name = '')
    {
        $arr = Qwin::run('Qwin_Helper_Array')->tophpCode($arr);
        if('' != $name)
        {
            $file_str = "<?php\r\n\$$name = $arr;\r\n";
        } else {
            $file_str = "<?php\r\nreturn $arr;\r\n";
        }
        Qwin::run('Qwin_Helper_File')->write($path, $file_str);
    }
}
