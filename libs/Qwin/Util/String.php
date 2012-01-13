<?php
/**
 * String
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2009-11-24 20:45:11
 */
class Qwin_Util_String
{
    /**
     * 替换字符串一次
     *
     * @param string $needle 搜索的字符串
     * @param string $replace 替换的字符串
     * @param string $haystack 搜索的内容
     * @return string
     */
    public static function replaceFirst($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     * 快速生成一个随机数
     *
     * @param int $length 长度,小于32
     * @return string
     * @see http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
     */
    public static function random($length)
    {
        return substr(md5(uniqid()), 0, $length);
        // Or return substr(base64_encode(rand(100000000,999999999)),0,10);
    }

    /**
     * Chop a string into a smaller string.
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.1.0
     * @link        http://aidanlister.com/2004/04/creating-a-string-exerpt-elegantly/
     * @param       mixed  $string   The string you want to shorten
     * @param       int    $length   The length you want to shorten the string to
     * @param       bool   $center   If true, chop in the middle of the string
     * @param       mixed  $append   String appended if it is shortened
     */
    public static function chop($string, $length = 60, $center = false, $append = null)
    {
        // Set the default append string
        if ($append === null) {
            $append = ($center === true) ? ' ... ' : ' ...';
        }

        // Get some measurements
        $len_string = strlen($string);
        $len_append = strlen($append);

        // If the string is longer than the maximum length, we need to chop it
        if ($len_string > $length) {
            // Check if we want to chop it in half
            if ($center === true) {
                // Get the lengths of each segment
                $len_start = $length / 2;
                $len_end = $len_start - $len_append;

                // Get each segment
                $seg_start = substr($string, 0, $len_start);
                $seg_end = substr($string, $len_string - $len_end, $len_end);

                // Stick them together
                $string = $seg_start . $append . $seg_end;
            } else {
                // Otherwise, just chop the end off
                $string = substr($string, 0, $length - $len_append) . $append;
            }
        }

        return $string;
    }

    /**
     * 截取字符串
     *
     * @param string $content 提供的字符串
     * @param int $length 要截取的字符串长度
     */
    public static function substr($content, $length)
    {
        if ($length && strlen($content) > $length) {
            $str = substr($content, 0, $length);
            $hex = '';
            $len = strlen($str) - 1;
            for ($i = $len; $i >= 0; $i -= 1) {
                $ch = ord($str[$i]);
                $hex .= ' ' . $ch;
                if (($ch & 128) == 0 || ($ch & 192) == 192) {
                    $ct = substr($str, 0, $i) . '...';
                    return $ct;
                }
            }
            return $str . $hex . '...';
        }
        return $content;
    }

    /**
     * 分割字符串为数字形式
     *
     * @param string $data 数据
     * @param string $separator 分割符1
     * @param string $separator2 分隔符2
     * @return array
     * @todo example
     */
    public static function split2d($data, $separator = ',', $separator2 = '.')
    {
        if (null == $data) {
            return null;
        }
        $data = explode($separator, $data);
        foreach ($data as $key => $value) {
            $pos = strpos($value, $separator2);
            if (false !== $pos) {
                $data[$key] = array(
                    substr($value, 0, $pos),
                    substr($value, $pos + 1),
                );
            }
        }
        return $data;
    }

    /**
     * 分割查询字符串为数组形式
     *
     * @param string $data 查询字符串
     * @return array 查询数组
     */
    public static function splitQuery($data)
    {
        if (empty($data)) {
            return array();
        }
        $data = preg_split('/(?<!\\\\)\,/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($data as &$row) {
            $row = strtr($row, array('\,' => ','));
            $row = preg_split('/(?<!\\\\)\:/', $row, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($row as &$element) {
                $element = strtr($element, array('\:' => ':'));
            }
        }
        return $data;
    }

    public static function implodeQuery(&$data)
    {
        $result = '';
        foreach ($data as &$row) {
            // 不是合法格式
            if (!is_array($row) || !isset($row[0]) || !isset($row[1])) {
                continue;
            }
            $temp = strtr($row[0], array(':' => '\:')) . ':' . strtr($row[1], array(':' => '\:'));
            if (isset($row[2])) {
                $temp .= strtr($row[2], array(':' => '\:'));
            }
            $row = strtr($temp, array(',' => '\,'));;
        }
        return implode(',', $data);
    }
}
