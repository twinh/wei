<?php
/**
 * Css
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
 * @subpackage  Packer
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-11-10 20:21:15
 */

class Qwin_Packer_Css extends Qwin_Packer_Basic
{
    public function getHtmlTag($name = null, $file = 'packer.php')
    {
        if(null == $name)
        {
            $name = $this->_md5;
        }
        return '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '?name=' . $name . '&type=text/css&ext=.css" />';
    }

    public function convertContent($content, $file)
    {
        $fullPath = dirname($file);
        $pattern = "/url\((.+?)\)/i";
        $replacement = 'url(' . $fullPath . '/$1)';
        return preg_replace($pattern, $replacement, $content);
    }
}
