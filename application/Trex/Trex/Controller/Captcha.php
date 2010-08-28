<?php
/**
 * Captcha
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
 * @since       2010-08-27 10:40:28
 */

class Trex_Trex_Controller_Captcha extends Qwin_Trex_Controller
{
    public function actionIndex()
    {
        $number = mt_rand(1000,9999);
        Qwin_Class::run('-ses')->set('captcha', $number);
        Qwin::load('Project_Helper_Captcha');
        return Project_Helper_Captcha::create($number);
    }
}
