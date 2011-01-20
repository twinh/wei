<?php
/**
 * Common Class
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
 * @subpackage  CommonClass
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 12:18:00
 */

class Common_CommonClass_Controller_CommonClass extends Common_ActionController
{
    /**
     * on 函数
     */
    public function onAfterDb($data)
    {
        $commonClass = Qwin::run('Project_Helper_CommonClass');
        if('delete' != strtolower($this->_asc['action']))
        {
            $commonClass->write($data);
        } else {
            //$commonClass->delete($data);
        }
    }
}
