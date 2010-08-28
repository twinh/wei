<?php
/**
 * Language
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
 * @since       2010-08-28 16:40:44
 */

class Trex_Member_View_Language extends Trex_View
{
    public function __construct()
    {
        parent::__construct();
        $this->setElement('content', QWIN_RESOURCE_PATH . '/view/theme/' . $this->_theme . '/element/member-setting-language.php');
    }

    public function display()
    {
        $set = Qwin::run('-ini')->getSet();
        $urlLanguage = $this->urlLanguage;
        require $this->_layout;
    }
}
