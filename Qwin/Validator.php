<?php
/**
 * Validator
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
 * @version   2010-5-21 7:03:17 utf-8 中文
 * @since     2010-5-21 7:03:17 utf-8 中文
 */

class Qwin_Validator
{
    /**
     * 添加一个验证类
     * @param string $className
     * @return object
     */
    public function add($className)
    {
        $this->_class[$className] = $className;
        return $this;
    }

    public function call($method, $param)
    {
        foreach($this->_class as $class)
        {
            $object = Qwin_Class::run($class);
            if(method_exists($object, $method))
            {
                return call_user_func_array(
                    array($object, $method),
                    $param
                );
            }
        }
        return true;
    }
}