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
 * @version   2009-11-21 13:42 utf-8 中文
 * @since     2009-11-21 13:42 utf-8 中文
 */

class Admin_Controller_HttpError extends Qwin_Miku_Controller
{
    function Action404()
    {
        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
    	$this->__cp_content = 'Resource/View/AdminHttpError404';
    	$this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
    }

    function Action401()
    {
        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
    	$this->__cp_content = 'Resource/View/AdminHttpError401';
        require_once qw('-ini')->load('Resource/View/AdminControlPanel', false);
    }
}
