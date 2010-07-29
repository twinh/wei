<?php
/**
 * Qwin_Converter_String 的名称
 *
 * Qwin_Converter_String 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2009-10-31 01:19:04 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 */

class Qwin_Converter_String
{
    function converString($msg,$isurl=null){
        $msg = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','',$msg);
        $msg = str_replace(array("\0","%00","\r"),'',$msg);
        empty($isurl) && $msg = preg_replace("/&(?!(#[0-9]+|[a-z]+);)/si",'&amp;',$msg);
        $msg = str_replace(array("%3C",'<'),'&lt;',$msg);
        $msg = str_replace(array("%3E",'>'),'&gt;',$msg);
        $msg = str_replace(array('"',"'","\t",'  '),array('&quot;','&#39;','    ','&nbsp;&nbsp;'),$msg);
        return $msg;
    }
    
    /**
     * 转换路径为标准网址路径
     * @param string $path 不标准或未知路径
     * @return string 路径
     */
    function toUrlSeparator($path)
    {
        return str_replace('\\', '/', $path);
    }
    
    /**
     * 转换路径为标准PC上的路径
     * @param string $path
     * @return string
     */
    function toPathSeparator($path)
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
    
    function toBlank($str)
    {
        if(!$str)
        {
            return '&nbsp;';
        }
        return $str;
    }
    
    static function date($data, $format = 'Y-m-d')
    {
        return date($format, $data);
    }
    
    // pw
    function secureCode($mixed,$isint=false,$istrim=false)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::secureCode($value,$isint,$istrim);
            }
        } elseif ($isint) {
            $mixed = (int)$mixed;
        } elseif (!is_numeric($mixed) && ($istrim ? $mixed = trim($mixed) : $mixed)) {
            $mixed = str_replace(array("\0","%00","\r"),'',$mixed);
            $mixed = preg_replace(
                array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'),
                array('','&amp;'),
                $mixed
            );
            $mixed = str_replace(array("%3C",'<'),'&lt;',$mixed);
            $mixed = str_replace(array("%3E",'>'),'&gt;',$mixed);
            $mixed = str_replace(array('"',"'","\t",'  '),array('&quot;','&#39;','    ','&nbsp;&nbsp;'),$mixed);
        }
        return $mixed;
    }

    /**
     * 将文本转换成包含Html标签的代码
     * @param <type> $code 文本
     * @return <type> 包含Html标签的代码
     */
    function textToHtml($code)
    {
        $code = str_replace(
            array("\t", "\r\n", "\r", "\n"),
            array('&nbsp;&nbsp;&nbsp;&nbsp;', '<br />', '<br />', '<br />'),
            $code
        );
        return $code;
    }
    
    /**
    * 将提供的字符串转化为安全文件名称
    *
    * @param string $path 文件名称
    * @return string 安全的文件名称
    */
    public function secureFileName($path)
    {
        return str_replace(array('\\', '/', ':', '*', '<', '>', '?', '|'), '', $path);
    }
    
    /**
     * 截取字符串
     * 
     * @param string $content 提供的字符串 
     * @param int $length 要截取的字符串长度
     */
    public function utf8Substr($content,$length){
        if ($length && strlen($content)>$length) {
            $str = substr($content,0,$length);
            $hex = '';
            $len = strlen($str)-1;
            for ($i=$len;$i>=0;$i-=1) {
                $ch = ord($str[$i]);
                $hex .= " $ch";
                if (($ch & 128)==0 || ($ch & 192)==192) {
            $ct = substr($str,0,$i).'...';
                return $ct;
                }
            }
            return $str.$hex.'...';
        }
        return $content;
    }
    
    /**
     * @abstract  产生指定长度随机字符串
     * @param int $len
     */
    public function getRandStr($len){
        $code = '';
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$l = strlen($str);
        $l = 62;
        for($i = 1;$i <= $len;$i++){ 
            $num = rand(0, $l-1);
            $code .= $str[$num];
        }
        return $code;
    }
    

    /**
     * 设置一个变量
     * @param mixed $var
     * @return <type>
     */
    public function set(&$var)
    {
        !isset($var) && $var = '';
        return $var;
    }

    /**
     * 产生一个Uuid号
     * @param  $val 任意值,如果值存在,直接返回该值,否则产生一个新的Uuid
     * @return <type> Uuid
     */
    public function getUuid($val = NULL)
    {
        if($val)
        {
            return $val;
        }
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

    public function implode($arr, $code)
    {
        !is_array($arr) && $arr = array($arr);
        return implode($code, $arr);
    }

    public function explode($str, $code)
    {
        return explode($code, $str);
    }
}
