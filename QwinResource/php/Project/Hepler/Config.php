<?php
/**
 * Config
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
 * @version   2010-7-18 2:48:14
 * @since     2010-7-18 2:48:14
 */

class Project_Hepler_Config
{
    /**
     * 配置缓存数组
     * @var array
     */
    private $_cache;

    function  __construct()
    {
        $this->_cache = Qwin_Class::run('Qwin_Cache_List')->getCache('Config');
    }

    public function get($name)
    {
        return $this->_cache[$name];
    }

    public function set($name, $value)
    {
        $this->_cache[$name] = $value;
        return $this;
    }
}

function C($name)
{
    echo Qwin_Class::run('Project_Hepler_Config')->get($name);
}
