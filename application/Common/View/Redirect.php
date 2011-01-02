<?php
/**
 * Redirect
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
 * @package     Common
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:17:09
 */

class Common_View_Redirect extends Common_View
{
    public function __construct()
    {
        parent::__construct();
        $this->setElement('content', '<resource><theme>/<namespace>/element/<defaultModule>/<defaultController>/redirect<suffix>');
    }

    public function preDisplay()
    {
        $message = $this->message;

        switch($this->method)
        {
            case null :
            case '' :
            case 'goback' :
                //echo 'history.go(-1);';
                break;
            case 'close' :
                //echo 'window.close();';
                break;
            default :
                $url = $this->method;
                break;
        }

        $this->_data += get_defined_vars();
    }
}
