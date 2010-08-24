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
    public function __construct()
    {
        
    }

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
}
