<?php
/**
 * String
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
     * 消除非法字符
     *
     * @see http://www.codebelay.com/killxss.phps
     * @see http://zend-framework-community.634137.n4.nabble.com/XSS-Prevention-with-Zend-Framework-td661493.html
     * @todo 调整简化
     */
    public static function escape($string, $esc_type = 'htmlall')
    {
        switch ($esc_type) {
            case 'html':
                //return htmlspecialchars($string, ENT_NOQUOTES);
                return str_replace(array('<', '>'), array('&lt;' , '&gt;'), $string);

            case 'quotes':
                // escape unescaped single quotes
                return preg_replace("%(?<!\\\\)'%", "\\'", $string);

            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

            case 'mail':
                // safe way to display e-mail address on a web page
                return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);
                
            case 'css':
                $string = str_replace(array('<', '>', '\\'), array('&lt;', '&gt;', '&#47;'), $string);
                // get rid of various versions of javascript
                $string = preg_replace(
                        '/j\s*[\\\]*\s*a\s*[\\\]*\s*v\s*[\\\]*\s*a\s*[\\\]*\s*s\s*[\\\]*\s*c\s*[\\\]*\s*r\s*[\\\]*\s*i\s*[\\\]*\s*p\s*[\\\]*\s*t\s*[\\\]*\s*:/i',
                        'blocked', $string);
                $string = preg_replace(
                        '/@\s*[\\\]*\s*i\s*[\\\]*\s*m\s*[\\\]*\s*p\s*[\\\]*\s*o\s*[\\\]*\s*r\s*[\\\]*\s*t/i',
                        'blocked', $string);
                $string = preg_replace(
                        '/e\s*[\\\]*\s*x\s*[\\\]*\s*p\s*[\\\]*\s*r\s*[\\\]*\s*e\s*[\\\]*\s*s\s*[\\\]*\s*s\s*[\\\]*\s*i\s*[\\\]*\s*o\s*[\\\]*\s*n\s*[\\\]*\s*/i',
                        'blocked', $string);
                $string = preg_replace('/b\s*[\\\]*\s*i\s*[\\\]*\s*n\s*[\\\]*\s*d\s*[\\\]*\s*i\s*[\\\]*\s*n\s*[\\\]*\s*g:/i', 'blocked', $string);
                    return $string;

            case 'htmlall':
                return htmlentities($string, ENT_QUOTES);

            case 'url':
                return rawurlencode($string);
                
            case 'query':
                return urlencode($string);

            case 'hex':
                // escape every character into hex
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '%' . bin2hex($string[$x]);
                }
                return $s_return;

            case 'hexentity':
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '&#x' . bin2hex($string[$x]) . ';';
                }
                return $s_return;

            case 'decentity':
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '&#' . ord($string[$x]) . ';';
                }
                return $s_return;

            case 'nonstd':
                // escape non-standard chars, such as ms document quotes
                $_res = '';
                for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
                    $_ord = ord($string{$_i});
                    // non-standard char, escape it
                    if($_ord >= 126){
                        $_res .= '&#' . $_ord . ';';
                    } else {
                        $_res .= $string{$_i};
                    }
                }
                   return $_res;

            default:
                return $string;
        }
    }

    /**
     * 过滤特殊字符
     *
     * @param string $string 字符串
     * @return string
     */
    public static function filter($string)
    {
        // 其他 '=', "\0", "%00", "\r", '\0', '%00', '\r' ?
        // 过滤不可见字符
        return preg_replace(
            array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '/&(?!(#[0-9]+|[a-z]+);)/is'),
            array('', '&amp;'),
            // 替换html标签,制表符,换行符
            str_replace(
                array('&', '%3C', '<', '%3E', '>', '"', '\'', "\t", ' '),
                array('&amp;', '&lt;', '&lt;', '&gt;', '&gt;', '&quot;', '&#39;', '    ', '&nbsp;'),
                $string
            )
        );
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
     * 产生一个UUID号
     *
     * @return string UUID
     * @see http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     * @see http://php.net/manual/en/function.uniqid.php
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

    /**
     * 分割字符串为数字形式
     *
     * @param <type> $data
     * @param <type> $separator 分割符1
     * @param <type> $separator2 分隔符2
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
}
