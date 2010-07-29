<?php
/**
 * Common
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
 * @version   2010-5-21 7:14:48 utf-8 中文
 * @since     2010-5-21 7:14:48 utf-8 中文
 */

class Qwin_Validator_Common
{
    public function required($val)
    {
        return '' != trim($val);
    }

    public function minlength($val, $param)
    {
        return strlen($val) >= $param;
    }

    public function maxlength($val, $param)
    {
        return strlen($val) <= $param;
    }

    public function rangelength($val, $param1, $param2)
    {
        $len = strlen($val);
        return $len >= $param1 && $len <= $param2;
    }

    public function equalTo($val, $param)
    {
        $param = strtr($param, array('#' => '', '.' => ''));
        if(isset($_POST[$param]))
        {
            return $_POST[$param] == $val;
        }
        return true;
    }

    /**
     *
     * @param <type> $val
     * @return <type>
     * @todo ereg
     */
    public function email($val)
    {
        return @ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $val);
    }
}