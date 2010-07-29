<?php
/**
 * Access
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
 * @version   2010-7-27 15:41:46
 * @since     2010-7-27 15:41:46
 */

abstract class Qwin_Metadata_AccessArray implements ArrayAccess
{
    protected $_data;

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->_data[$name]))
        {
            return $this->_data[$name];
        }
        return NULL;
    }

    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        if(isset($this->_data[$offset]))
        {
            return $this->_data[$offset];
        }
        return NULL;
    }

    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }
}
