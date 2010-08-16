<?php
/**
 * List
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
 * @version   2010-3-7 15:55:50
 * @since     2010-3-7 15:55:50
 */

/**
 * @see Qwin_Cache
 */
require_once 'Qwin/Cache.php';

class Qwin_Cache_List extends Qwin_Cache
{
    private $_path;
    private $_table;

    function __construct()
    {
        $this->_path = QWIN_ROOT_PATH . '/Cache/Php/List/';
    }

    /**
     * 配置缓存的路径
     */
    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }

    function getCache($name)
    {
        if(file_exists($this->_path . $name . '.php'))
        {
            return require $this->_path . $name . '.php';
        }
        return null;
    }

    function setCache($name)
    {
        $self = Qwin::run('-c');
        $query = Qwin::run('-qry');
        $db = Qwin::run('-db');

        $sql = $query->getList('');
        $data = $db->getList($sql);
        $this->writeCache($data, $name);
    }

    public function writeCache($data, $name)
    {
        parent::writeArr($data, $this->_path . $name . '.php');
    }
}

