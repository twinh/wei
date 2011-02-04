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
 * @subpackage  Util
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-04-29 04:49:37
 */

class Qwin_Helper_Util
{
    /**
     * 获取Ip地址
     *
     * @return string Ip地址
     */
    public static function getIp()
    {
        if (isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
            $ip = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) {
            $ip = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
        } elseif (isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
            $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = 'Unknown';
        }
        return $ip;
    }

    /**
     * 产生一个UUID号
     *
     * @return string UUID
     * @see http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     */
    public static function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
