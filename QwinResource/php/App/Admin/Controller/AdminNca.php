<?php
 /**
 * Namespace, Controller, Action 管理
 *
 * nca后台控制器
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
 * @version   2009-11-21 13:20
 * @since     2009-11-21 13:20
 */

class Admin_Controller_Nca extends Qwin_Miku_Controller
{
    /**
     * 列表
     */
    function actionDefault()
    {
        return Qwin::run('Qwin_Action_List');
    }

    /**
     * 添加
     */
    function actionAdd()
    {
        return Qwin::run('Qwin_Action_Add');
    }

    /**
     * 编辑
     */
    function actionEdit()
    {
        return Qwin::run('Qwin_Action_Edit');
    }

    /**
     * 删除
     */
    function actionDelete()
    {
        return Qwin::run('Qwin_Action_Delete');
    }

    /**
     * 列表的 json 数据
     */
    function actionJsonList()
    {
        return Qwin::run('Qwin_Action_JsonList');
    }

    /**
     * 查看
     */
    function actionShow()
    {
        return Qwin::run('Qwin_Action_Show');
    }

    /**
     * 筛选
     */
    function actionFilter()
    {
        return Qwin::run('Qwin_Action_Filter');
    }


	# 根据目录,获取 nca 数据
	// 数据库 id
	private $_id = 1;
	// nca 数组数据
	private $_nca = array();
	function actionUpdate_nca()
	{
		set_time_limit(0);
		qw('-ini')->loadClass(array('goolge_translate_api', 'data', 'resource'));

		$namespace = $this->_getNamespace();
		$controller = $this->_getController($namespace);
		$action = $this->_getAction($namespace, $controller);

		// 清空数据表
		$sql = "TRUNCATE TABLE `" . qw('-qry')->getTable() . "`";
		qw('-db')->Query($sql);
		// 加入
		foreach($this->_nca as $val)
		{
			$sql = qw('-qry')->getIU($val);
			qw('-db')->Insert($sql);
		}

	}

	private function _getNamespace()
	{
		$namespace = array();
		$dir_arr = scandir(ROOT_PATH . DS . 'app');
		foreach($dir_arr as $val)
		{
			if($val != '.' &&
				$val != '..' &&
				substr($val, 0, 1) != '.' &&
				substr($val, 0, 1) != '_'
			 ){
				// 获取 namespace, 逆向构造 sql 数据
				$this->_nca[$this->_id] = $namespace[$this->_id] = array(
					'id' => $this->_id,
					'category_id' => 0,
					'type' => 1013001,
					'name' => $val,
					'descrip' => $this->getDescrip($val),
					'state_code' => 1002001,
				);
				$this->_id++;
			}
		}
		return $namespace;
	}

	private function _getController($namespace)
	{
		foreach($namespace as $val)
		{
			$file_arr = scandir(ROOT_PATH . DS . 'app' . DS . $val['name'] . DS . 'controller');
			foreach($file_arr as $val_2)
			{
				if($val_2 == '.' ||
					$val_2 == '..' ||
					substr($val_2, 0, 1) == '.' ||
					substr($val_2, 0, 1) == '_'
				 ){
					continue;
				} elseif(substr($val_2, -4) == '.php') {
					$name = basename($val_2, '.php');
					$this->_nca[$this->_id] = $controller[$this->_id] = array(
						'id' => $this->_id,
						'category_id' => $val['id'],
						'type' => 1013002,
						'name' => $name,
						'descrip' => $this->getDescrip($name),
						'state_code' => 1002001,
					);
					$this->_id++;
				}
			}
		}
		return $controller;
	}

	private function _getAction($namespace, $controller)
	{
		foreach($controller as $val)
		{
			// 控制器文件
			$file = ROOT_PATH . DS . 'app' . DS . $namespace[$val['category_id']]['name'] . DS . 'controller' . DS . $val['name'] .'.php';
			if(!file_exists($file))
			{
				continue;
			}
			require_once $file;
			// 控制器类名
			$class_name = 'Controller_' . $namespace[$val['category_id']]['name'] . '_' . $val['name'];
			if(!class_exists($class_name))
			{
				continue;
			}
			// method
			$class_method = get_class_methods($class_name);
			foreach($class_method as $val_2)
			{
				if(substr($val_2, 0, 6) == 'action')
				{
					$name = substr($val_2, 6);
					$this->_nca[$this->_id] = $action[$this->_id] = array(
						'id' => $this->_id,
						'category_id' => $val['id'],
						'type' => 1013003,
						'name' => strtolower($name),
						'descrip' => $this->getDescrip($name),
						'state_code' => 1002001,
					);
					$this->_id++;
				}
			}
		}
		return $action;
	}


	# 列表,表单显示
	static $nca_list;
	// form
	function getNcaCategory()
	{
		$nca_list[0] = '请选择';
		self::getNcaList();
		foreach(self::$nca_list as $key => $val)
		{
			// namespace
			if($val['type'] == '1013001')
			{
				$nca_list[$val['id']] = $val['name'];
				//unset($category_list[$key]);
				foreach(self::$nca_list as $key_2 => $val_2)
				{
					if($val_2['category_id'] == $val['id'])
					{
						$nca_list[$val_2['id']] = '&nbsp;&nbsp;|-' . $val_2['name'];
						//unset($category_list[$key_2]);
					}
				}
			}
		}
		return $nca_list;
	}

	// list
	// TODO cache
	function getNcaCategory2($id)
	{
		self::getNcaList();
		foreach(self::$nca_list as $key => $val)
		{
			// namespace
			if($val['type'] == '1013001')
			{
				$nca_list[$val['id']] = $val['name'];
				//unset($category_list[$key]);
				foreach(self::$nca_list as $key_2 => $val_2)
				{
					if($val_2['category_id'] == $val['id'])
					{
						$nca_list[$val_2['id']] = $val['name'] . '_' . $val_2['name'];
						//unset($category_list[$key_2]);
					}
				}
			}
		}
		if($nca_list[$id])
		{
			return $nca_list[$id];
		}
		return '-';
	}

	function getNcaList()
	{
		if(!self::$nca_list)
		{
			qw('-qry')->setTable('nca');
			$query_arr = array(
				'SELECT' => "id, category_id, type, name",
				'WHERE' => "`type` <> 1013003",
			);
			$sql = qw('-qry')->getList($query_arr);
			self::$nca_list = qw('-ini')->$db->getList($sql);
		}
	}

	static private $_descrip = array(
		'default' => '默认/列表',
		'add' => '添加',
		'edit' => '编辑',
		'delete' => '删除',
		'filter' => '筛选',
		'nca' => 'NCA',
		'update_nca' => '更新NCA',
		'thumb' => '缩略图',
		'jqueryfiletree' => 'jQuery文件树',
		'ajaxupload' => 'Ajax文件上传'
	);
	function getDescrip($name)
	{
		$lower_name = strtolower($name);
		if(isset(self::$_descrip[$lower_name]))
		{
			return self::$_descrip[$lower_name];
		} else {
			$name = str_replace('_', ' ', $name);
			$name_arr = qw('-arr')->explodeByUppercase($name);
			$name = implode(' ', $name_arr);
			return GoolgeTranslateApi::translate($name);
		}
	}
}
