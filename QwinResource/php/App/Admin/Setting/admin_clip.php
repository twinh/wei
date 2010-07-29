<?php
 /**
 * 碎片
 *
 * 碎片后台模型
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
 * @version   2009-11-21 15:15 utf-8 中文
 * @since     2009-11-21 15:15 utf-8 中文
 */

class Setting_Admin_Clip
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
						'isUrlQuery' => false,
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
							array('attr', 'is_optional', true),
							array('Validation::isSqlId', 'AUTO'),
							array('Validation::isLengthBetween', 1, 10, 'AUTO'),
						),
					),
				),
				'group_id' => array(
					'basic' => array(
						'title' => '分组',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => Mvc::getCcList('clip_group'),
						'name' => 'group_id',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => true,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'attr' => array('Mvc::converCc', 'clip_group'),
						),
						'validation' => array(
							array('Validation::isInArray', Mvc::getCcList('clip_group'), 'AUTO'),
						),
					),
				),
				'name' => array(
					'basic' => array(
						'title' => '名称',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'name',
					),
					'attr' => array(
						'isUrlQuery' => false,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
						),
						'validation' => array(
							// TODO 唯一
							array('Validation::isLengthBetween', 3, 40, 'AUTO'),
						),
					),
				),
				'var_name' => array(
					'basic' => array(
						'title' => '变量名称',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'var_name',
					),
					'attr' => array(
						'isUrlQuery' => false,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
						),
						'validation' => array(
							// TODO 唯一
							array('Validation::isLengthBetween', 3, 40, 'AUTO'),
						),
					),
				),
				'type' => array(
					'basic' => array(
						'title' => '表单类型',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '1006004',
						'_resource' => Mvc::getCcList('form_type'),
						'name' => 'type',
					),
					'attr' => array(
						'isUrlQuery' => true,
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => true,
					),
					'operation' => array(
						'conversion' => array(
							'add' => '',
							'edit' => '',
							'show' => '',
							'attr' => array('Mvc::converCc', 'form_type'),
						),
						'validation' => array(
							array('Validation::isInArray', Mvc::getCcList('form_type'), 'AUTO'),
						),
					),
				),
				'code' => array(
					'basic' => array(
						'title' => '代码',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'textarea',
						'_value' => '',
						'name' => 'code',
					),
					'attr' => array(
						'isUrlQuery' => false,
						'isList' => false,
						'isSqlField' => true,
						'is_sql_query' => false,
						'is_search' => false,
					),
					'operation' => array(
						'conversion' => array(
							//'edit' => array('qw('-str')->secureCode'),
						),
						'validation' => array(
							array('Validation::isLengthBetween', 1, 65535, 'AUTO'),
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
						'_resource' => Mvc::getCcList('state_code'),
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
							array('Validation::isInArray', Mvc::getCcList('state_code'), 'AUTO'),
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
				'table' => 'clip'
			),
			// 页面显示
			'page' => array(
				'title' => '碎片',
				'descrip' => '碎片管理',
				'rowNum' => 10,
			),
		);
	}
}
