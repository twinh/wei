<?php
/**
 * Js
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
 * @since       2010-11-10 20:21:09
 */

class Qwin_Packer_Js extends Qwin_Packer_Basic
{
    public function getHtmlTag($name = null, $file = 'packer.php')
    {
        if(null == $name)
        {
            $name = $this->_md5;
        }
        return '<script type="text/javascript" src="' . $file . '?name=' . $name .  '&type=application/javascript&ext=.js"></script>';
    }
}
