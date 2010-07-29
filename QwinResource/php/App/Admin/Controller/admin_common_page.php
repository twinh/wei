<?php
 /**
 * 通用页面
 *
 * 通用页面的后台控制器
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
 * @version   2009-11-21 13:02 utf-8 中文
 * @since     2009-11-21 13:02 utf-8 中文
 */

class Controller_Admin_Common_page extends QW_Controller
{
	// action 函数
	// 列表
	function actionDefault()
	{
		return Qwin_Class::run('Qwin_Action_List');
	}

	// 添加
	function actionAdd()
	{
		return Qwin_Class::run('Qwin_Action_Add');
	}

	// 编辑
	function actionEdit()
	{
		return Qwin_Class::run('Qwin_Action_Edit');
	}

	// 删除
	function actionDelete()
	{
		return Qwin_Class::run('Qwin_Action_Delete');
	}

	// json 数据
	function actionJsonList()
	{
		return Qwin_Class::run('Qwin_Action_JsonList');
	}

	// 查看
	function actionShow()
	{
		return Qwin_Class::run('Qwin_Action_Show');
	}

	// 筛选
	function actionFilter()
	{
		return Qwin_Class::run('Qwin_Action_Filter');
	}

	function converListOperate($value, $data)
	{
		return '<a target="_blank" href="' . url('common_page.php',  array('page' => $data['page_name'])) . '">查看</a>';
	}
}
