<?php
 /**
 * 后台用户
 *
 * 后台用户后台控制器
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-01-31 23:49
 * @since     2010-01-31 23:49
 */

class Admin_Controller_Sidebar extends Qwin_Miku_Controller
{
    function subActionDefault()
    {
        $menu_list = Qwin::run('Qwin_Cache_List')->getCache('admin_menu');
        $this->__view = array(
            'menu_list' => $menu_list,
        );
    	$this->loadView(qw('-ini')->load('Resource/View/AdminSidebar', false));
    }
}
