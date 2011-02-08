<?php
/**
 * Html
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
 * @since       2011-01-29 14:16:48
 */

class Qwin_Util_Html
{
    /**
     * 生成Js链接
     *
     * @param string $file 文件路径
     * @return string
     */
    public static function jsTag($file)
    {
        return '<script type="text/javascript" src="' . htmlspecialchars($file) . '"></script>';
    }

    /**
     * 生成Css链接
     *
     * @param string $file 文件路径
     * @param string $media 媒介类型
     * @return string
     */
    public static function cssLinkTag($file, $media = 'all')
    {
        return '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . htmlspecialchars($file) . '" />';
    }

    /**
     * 为文字增加样式
     *
     * @param string $title
     * @param string $fontStyle 字体样式,用|隔开,如 i|u|b
     * @param sting $color 合法的颜色值,如 red,#000
     * @return string 转换过的标题
     */
    public static function decorateWord($word, $fontStyle = null, $color = null)
    {
        $style = '';
        $fontStyle = explode('|', (string)$fontStyle);
        foreach($fontStyle as $type) {
            switch ($type) {
                case 'b' :
                    $style .= ' font-weight:  bold;';
                    break;
                case 'i' :
                    $style .= ' font-style: italic;';
                    break;
                case 'u' :
                    $style .= ' text-decoration:  underline;';
                    break;
                default :
                    break;
            }
        }
        if($color) {
            $style .= ' color: ' . $color;
        }
        return '<span style="'. $style . '">' . $word . '</span>';
    }

    /**
     * 创建一个图片标签
     *
     * @param string $src 图片地址
     * @param string $alt 图片描述
     * @param string $width 图片宽度
     * @param string $height 图片高度
     * @param string $attr 附加属性
     * @return string
     */
    public static function img($src, $alt = null, $width = null, $height = null, $attr = null)
    {
        null != $alt    && $attr .= ' alt="' . $alt . '"';
        null != $width  && $attr .= ' width="' . $width . '"';
        null != $height && $attr .= ' height="' . $height . '"';
        return '<img src="' . $src . $attr . ' />';
    }

    /**
     * 创建一个链接
     *
     * @param string $url 链接地址
     * @param string $title 文字
     * @param string $target 打开的窗口目标
     * @return string
     */
    public static function link($url, $title = null, $target = null)
    {
        null == $title && $title = $url;
        null != $target && $target = ' target="' . $target . '"';
        return '<a' . $target . ' href="' . $url . '">' . $title . '</a>';
    }

    /**
     * 将文本转换成包含Html标签的代码
     * 
     * @param string $text 文本数据
     * @return string 包含Html标签的代码
     */
    public static function textToHtml($text)
    {
        return str_replace(
            array("\t"),
            array('&nbsp;&nbsp;&nbsp;&nbsp;'),
            nl2br($text)
        );
    }
}