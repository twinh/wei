<?php
/**
 * Zhcn
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
 * @subpackage  AdminMenu
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 19:36:00
 */

class Common_AdminMenu_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data +=  array(
            'LBL_FIELD_CATEGORY' => '分类',
            'LBL_FIELD_URL' => '地址',
            'LBL_FIELD_ORDER' => '顺序',
            'LBL_FIELD_TARGET' => '链接目标',

            'LBL_MODULE_ADMIN_MENU' => '后台菜单',
        );
    }
}
