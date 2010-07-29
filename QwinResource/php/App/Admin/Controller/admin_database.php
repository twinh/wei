<?php
 /**
 * 数据库操作
 *
 * 数据库操作后台控制器,包括导入,导出,删除备份数据
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
 * @version   2009-11-21 13:06 utf-8 中文
 * @since     2009-11-21 13:06 utf-8 中文
 */

class Controller_Admin_Database extends QW_Controller
{
	// 备份的数据
	function actionDefault()
	{
		qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
		mysqlData::$db = $this->db;
		mysqlData::$cache_path = ROOT_PATH . '/cache/sql';
		$sql_file = mysqlData::getSqlFile();
		
		$this->__view['sql_file'] = $sql_file;
	}
	
	function actionImport()
	{
		@set_time_limit(0);
		qw('-ini')->loadClass(array('qfile', 'file'));
		qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
		mysqlData::$db = $this->db;
		mysqlData::$cache_path = ROOT_PATH . '/cache/sql';
		$file = qw('-ini')->g('file');
		mysqlData::importSqlFile($file);
		QMsg::show('导入成功!', url(array('admin', 'database')));
		break;
	}
	
	// 删除
	function actionDelete()
	{
		qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
		$file = qw('-ini')->g('file');
		mysqlData::$cache_path = ROOT_PATH . '/cache/sql';
		if(mysqlData::delSqlFile($file))
		{
			QMsg::show('删除成功!', url(array('admin', 'database')));
		} else {
			QMsg::show('删除失败,请返回检查!', 'goback');
		}
	}
		
	function actionBackup()
	{
		if(!$_POST)
		{
			qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
			mysqlData::$db = $this->db;
			$table = mysqlData::getTable();
			$this->__view['table'] = $table;
		} else {
			@set_time_limit(0);
			qw('-ini')->loadClass(array('qfile', 'file'));
			qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
			$file_name = qw('-ini')->p('file_name');

			// 初始化
			mysqlData::$db = $this->db;
			mysqlData::$cache_path = ROOT_PATH . '/cache/sql';
			$table = mysqlData::getTable();
			mysqlData::outSqlFile($table, $file_name);
			QMsg::show('备份成功!', url(array('admin', 'database')));
		}
	}
}
