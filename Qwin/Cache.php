<?php
/**
 * Cache
 *
 * Copyright (c) 2009-2010 Twin Huang. All rights reserved.
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
 * @author    Twin Huang <Twin Huang>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-3-12 15:44:19
 * @since     2010-3-12 15:44:19 utf-8 中文
 * $todo      允许自定义缓存引擎
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
    public function set($name, $var = NULL, $expireTime = 0)
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
        return NULL;
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
            return Qwin_Class::load('Qwin_Cache_' . $engine);
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
        $arr = Qwin_Class::run('Qwin_Helper_Array')->tophpCode($arr);
        if('' != $name)
        {
            $file_str = "<?php\r\n\$$name = $arr;\r\n";
        } else {
            $file_str = "<?php\r\nreturn $arr;\r\n";
        }
        Qwin_Class::run('Qwin_Helper_File')->write($path, $file_str);
    }
}