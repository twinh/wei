<?php

/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Escape
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-1-12 17:53:49
 */
class Qwin_Escape extends Qwin_Widget
{
    /**
     * 消除非法字符
     *
     * @see http://www.codebelay.com/killxss.phps
     * @see http://zend-framework-community.634137.n4.nabble.com/XSS-Prevention-with-Zend-Framework-td661493.html
     * @todo 简化
     */
    public function call($esc_type = 'html')
    {
        // 其他 '=', "\0", "%00", "\r", '\0', '%00', '\r' ?
        // 过滤不可见字符
        /*$this->source = preg_replace(
            array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '/&(?!(#[0-9]+|[a-z]+);)/is'),
            array('', '&amp;'),
            // 替换html标签,制表符,换行符
            str_replace(
                array('&', '%3C', '<', '%3E', '>', '"', '\'', "\t", ' '),
                array('&amp;', '&lt;', '&lt;', '&gt;', '&gt;', '&quot;', '&#39;', '    ', '&nbsp;'),
                $this->source
            )
        );
        return $this->invoker;*/
        
        $string = &$this->source;
        switch ($esc_type) {
            case 'html':
                $string = htmlspecialchars($string);
                //$string = str_replace(array('<', '>'), array('&lt;' , '&gt;'), $string);
                break;

            case 'quotes':
                // escape unescaped single quotes
                $string = preg_replace("%(?<!\\\\)'%", "\\'", $string);
                break;

            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                $string = strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
                break;
                
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
                break;

            case 'htmlall':
                $string = htmlentities($string, ENT_QUOTES);
                break;

            case 'url':
                $string = rawurlencode($string);
                break;
                
            case 'query':
                return urlencode($string);

            case 'hex':
                // escape every character into hex
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '%' . bin2hex($string[$x]);
                }
                $string = $s_return;
                break;

            case 'hexentity':
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '&#x' . bin2hex($string[$x]) . ';';
                }
                $string = $s_return;
                break;

            case 'decentity':
                $s_return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $s_return .= '&#' . ord($string[$x]) . ';';
                }
                $string = $s_return;
                break;

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
                $string = $_res;
                break;

            default:
                break;
        }
        
        $this->source = $string;
        return $this->invoker;
    }
}