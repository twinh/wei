<?php
/**
 * JQuery
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
 * @package     Qwin
 * @subpackage  Util
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-02-04 21:20:39
 */

class Qwin_Util_JQuery
{
    public static function link($url, $title = null, $icon = 'ui-icon-info', $aClass = null, $target = '_self', $id = null)
    {
        isset($id) && $id = ' id="' . $id . '"';
        return '<a' . $id .' target="' . $target . '" href="' . $url
               . '" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary '
               . $aClass . '" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon '
               . $icon . '"></span><span class="ui-button-text">' . $title . '</span></a>' . PHP_EOL;
    }
}