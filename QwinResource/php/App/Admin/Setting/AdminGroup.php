<?php
 /**
 * 用户组
 *
 * 用户组后台模型
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
 * @version   2009-11-21 15:54 utf-8 中文
 * @since     2009-11-21 15:54 utf-8 中文
 */

class Admin_Setting_Group extends Qwin_Miku_Setting
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
						'order' => 0,
						'group' => 2002001,
					),
					'form' => array(
						'_type' => 'hidden',
						'_value' => '',
						'name' => 'id',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
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
				'name' => array(
					'basic' => array(
						'title' => '名称',
						'descrip' => '',
						'order' => 5,
						'group' => 2002001,
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'_group' => '分类1',
						'name' => 'name',
						'class' => 'name',
					),
					'attr' => array(
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						'edit' => '',
						'show' => '',
						'list' => '',
					),
					'validation' => array(
						array('Validation::isLengthBetween', 1, 20, 'AUTO'),
					),
				),
				'description' => array(
					'basic' => array(
						'title' => '描述',
						'descrip' => '',
						'order' => 10,
						'group' => 2002002,
					),
					'form' => array(
						'_type' => 'textarea',
						'_value' => '',
						'_group' => '分类1',
						'name' => 'description',
					),
					'attr' => array(
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						'edit' => '',
						'show' => '',
						'list' => '',
					),
					'validation' => array(
						array('Validation::isLengthBetween', 1, 200, 'AUTO'),
					),
				),
				'state_code' => array(
					'basic' => array(
						'title' => '状态',
						'descrip' => '',
						'order' => 15,
						'group' => 2002002,
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_group' => '分类2',
						'_resource' => parent::getCcList('state_code'),
						'name' => 'state_code',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						'edit' => '',
						'show' => '',
						'attr' => array('parent::converCc', 'state_code'),
					),
					'validation' => array(
						array('Validation::isInArray', parent::getCcList('state_code'), 'AUTO'),
					),
				),
				'operation' => array(
					'basic' => array(
						'title' => '操作',
						'descrip' => '',
						'order' => 20,
					),
					'form' => array(
						'_type' => 'custom',
						'_value' => '',
						'name' => 'operate',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => false,
						'is_sql_query' => false,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						'edit' => '',
						'show' => '',
						'list' => '',
					),
					'validation' => array(
					),
				),
			),
			// 附加属性
			'fieldExt' => array(
				'key' => 'id',
			),
			// 核心
			'core' => array(
				'table' => 'admin_group'
			),
			// 页面显示
			'page' => array(
				'title' => '用户组',
				'descrip' => '用户组管理',
				'rowNum' => 10,
			),
		);
	}
}
