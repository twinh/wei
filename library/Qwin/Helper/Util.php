<?php
/**
 * Util
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-04-29 04:49:37
 */

class Qwin_Helper_Util
{
    public function getStyle()
    {
        $config = Qwin::run('-ini')->getConfig();
        $configStyle = $config['interface']['style'];
        $styleArr = array(
                Qwin::run('-url')->g('style'),
                Qwin::run('-ses')->get('style'),
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
        Qwin::run('-ses')->set('style', $style);
        return $style;
    }

    public static function getIp()
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
