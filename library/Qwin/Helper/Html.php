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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-24 18:02:17
 */

class Qwin_Helper_Html
{
    /**
     * 为标题增加样式
     *
     * @param string $title
     * @param string $fontStyle 字体样式,用|隔开,如 i|u|b
     * @param sting $color 合法的颜色值,如 red,#000
     * @return string 转换过的标题
     */
    public static function titleDecorator($title, $fontStyle = null, $color = null)
    {
        $style = '';
        $fontStyle = explode('|', $fontStyle);
        foreach($fontStyle as $type)
        {
            switch($type)
            {
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
        if(null != $color)
        {
            $style .= ' color: ' . $color;
        }
        return '<span style="'. $style . '">' . $title . '</span>';
    }

    public static function jQueryButton($href, $title = null, $icon = 'ui-icon-info')
    {
        return '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="'
              . $title . '" href="' . $href . '"><span class="ui-icon ' . $icon .  '">' . $title . '</span></a>';
    }

    public static function img($src, $title = null, $width = null, $height = null, $additionAttr = null)
    {
        null == $title && $title = $src;
        null != $width && $width = ' width="' . $width . '"';
        null != $height && $height = ' height="' . $height . '"';
        return '<img src="' . $src . '" alt="' . $title . '"' . $width . $height . ' />';
    }

    public static function jQueryLink($url, $title, $icon, $aClass = null, $target = '_self', $id = null)
    {
        isset($id) && $id = ' id=' . $id . '"';
        return '<a' . $id .' target="' . $target . '" href="' . $url . '" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ' . $aClass . '" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ' . $icon . '"></span><span class="ui-button-text">' . $title . '</span></a>' . "\r\n";
    }
}
