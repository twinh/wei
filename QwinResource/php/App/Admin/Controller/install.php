<?php
 /**
 * 安装
 *
 * 安装
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
 * @version   2009-11-21 13:14 utf-8 中文
 * @since     2009-11-21 13:14 utf-8 中文
 */

class Controller_Default_Install extends QW_Controller
{
	function actionDefault()
	{
		qw('-ini')->loadClass(array('qform', 'form'));
		// js 验证数据
		$valid_data = $this->getJsValidCode($this->__meta['field']);
		# 视图
		$this->__view = array(
			'set' => $this->__meta,
			'valid_list' => $valid_data[0],
			'valid_msg' => $valid_data[1],
			'valid_title' => qw('-arr')->toJsObject($this->getSettingValue($this->__meta['field'], array('basic', 'title'))),
		);
		if($_POST)
		{
			qw('-ini')->loadClass(array('qfile', 'file'));
			$this->__query['action'] = 'db';
			$sql_field = $this->getSettingList($this->__meta['field'], 'is_config_field');
			$data = qw('-ini')->post($sql_field);
			// 获取转换函数的列表
			$conver_list = $this->getConverFunctionByAction($this->__meta['field'], $this->__query['action']);
			// 转换数据
			$data = $this->converDataByAction($data, $conver_list, $this->__query['action']);
			// 检验数据
			$valid_data = $this->getValidData($this->__meta['field']);
			$this->validData($this->__meta['field'], $valid_data, $data);
			
			$data['prefix'] = 'hl_';
			
			// 尝试连接数据库
			qw('-ini')->loadClass(array('db', 'db'));
			qw('-ini')->loadClass(array('query', 'db'));
			qw('-ini')->$db = new QDb($data);
			if(!qw('-ini')->$db->db_link || !@mysql_select_db($data['database'], qw('-ini')->$db->db_link))
			{
				$this->__view['sql_msg'] = "error ".mysql_errno()." : ".mysql_error();
			} else {				
				$config_data = QFile::read(ROOT_PATH . DS . 'cache/tpl/config/config.tpl');
				$config_data = str_replace(
					array('[server]', '[username]', '[password]', '[database]', '[prefix]', '[port]'),
					array($data['server'], $data['username'], $data['password'], $data['database'], $data['prefix'], $data['port']),
					$config_data
				);
				
				// 写入文件
				$config_data = QFile::write(ROOT_PATH . DS . 'common/config/config.php', $config_data);
				
				// 导入sql
				$sql_file = ROOT_PATH . DS . 'cache/sql/source.sql';
				if(!file_exists($sql_file))
				{
					die('数据库文件丢失 : ' . $sql_file);
				}
				
				qw('-ini')->loadClass(array('mysqldata', 'db', 'resource'));
				mysqlData::$db = $this->db;
				mysqlData::$cache_path = ROOT_PATH . '/cache/sql';
				$file = 'source.sql';
				mysqlData::importSqlFile($file);
				@unlink('install.php');
				QMsg::show('安装成功,现在将跳转到后台!', 'admin.php');
			}
		}
	}
}
