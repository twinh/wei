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
 * @version   2009-11-21 16:18
 * @since     2009-11-21 16:18
 */

class Admin_Setting_User extends Qwin_Miku_Setting
{
	public function defaultMetadata()
	{
		return array(
			// 基本属性
			'field' => array(
				'id' => array(
					'basic' => array(
						'title' => '编号',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'hidden',
						'_value' => '',
						'name' => 'id',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => false,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'list' => '',
						),
						'validation' => array(
							array('Validation::isSqlId', 'AUTO'),
							array('Validation::isLengthBetween', 0, 10, 'AUTO'),
						),
					),
				),
				'group_id' => array(
					'basic' => array(
						'title' => '用户组',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						// 另表..
						'_resource' => $this->getCategoryList('admin_group', array('请选择'), array('id', 'name')),
						'name' => 'group_id',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'attr' => array('Mvc::converCategory', 'admin_group', array('id', 'name')),
						),
						'validation' => array(
							array('Validation::isNot0', 'AUTO'),
							array('Validation::isLengthBetween', 1, 10, 'AUTO'),
						),
					),
				),
				'username' => array(
					'basic' => array(
						'title' => '用户名',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'username',
					),
					'attr' => array(
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'list' => '',
						),
						'validation' => array(
							array('Validation::isLengthBetween', 1, 40, 'AUTO'),
						),
					),
				),
				'nickname' => array(
					'basic' => array(
						'title' => '昵称',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'nickname',
					),
					'attr' => array(
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'list' => '',
						),
						'validation' => array(
							array('Validation::isLengthBetween', 1, 40, 'AUTO'),
						),
					),
				),
				'password' => array(
					'basic' => array(
						'title' => '密码',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'password',
						'_value' => '',
						'name' => 'password',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => false,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'list' => '',
						),
						'validation' => array(
							array('Validation::isLengthBetween', 1, 40, 'AUTO'),
						),
					),
				),
				'state_code' => array(
					'basic' => array(
						'title' => '状态',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => $this->getCcList('state_code'),
						'name' => 'state_code',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'attr' => array('Mvc::converCc', 'state_code'),
						),
						'validation' => array(
							array('Validation::isInArray', $this->getCcList('state_code'), 'AUTO'),
						),
					),
				),
			),
			// 附加属性
			'fieldExt' => array(
				'key' => 'id',
			),
			// 核心
			'core' => array(
				'table' => 'admin_user'
			),
			// 页面显示
			'page' => array(
				'title' => '用户',
				'descrip' => '用户管理',
				'rowNum' => 10,
			),
		);
	}
}
