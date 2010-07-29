<?php
/**
 * Util
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
 * @version   2010-4-29 4:49:37 utf-8 中文
 * @since     2010-4-29 4:49:37 utf-8 中文
 */

class Qwin_Hepler_Util
{
    public function getStyle()
    {
        $config = Qwin_Class::run('-ini')->getConfig();
        $configStyle = $config['interface']['style'];
        $styleArr = array(
                Qwin_Class::run('-url')->g('style'),
                Qwin_Class::run('-ses')->get('style'),
                $configStyle,
        );
        foreach($styleArr as $val)
        {
            if(null != $val)
            {
                $style = $val;
                break;
            }
        }
        Qwin_Class::run('-ses')->set('style', $style);
        return $style;
    }

    public function getIp()
    {
        if (isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]))
        {
            $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
        }
        elseif (isset($HTTP_SERVER_VARS["REMOTE_ADDR"]))
        {
            $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
        }
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
        {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        elseif (getenv("HTTP_CLIENT_IP"))
        {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        elseif (getenv("REMOTE_ADDR"))
        {
            $ip = getenv("REMOTE_ADDR");
        }
        else
        {
            $ip = "Unknown";
        }
        return $ip;
    }
}