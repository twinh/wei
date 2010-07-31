<?php
 /**
 * Namespace, Controller, Action 管理
 *
 * nca 后台控制器
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
 * @version   2009-11-21 16:09
 * @since     2009-11-21 16:09
 */

class Admin_Setting_Nca extends Qwin_Miku_Setting
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
				'category_id' => array(
					'basic' => array(
						'title' => '类别',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => Admin_Controller_Nca::getNcaCategory(),
						'name' => 'category_id',
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
							'attr' => array('Controller_Admin_Nca::getNcaCategory2'),
						),
						'validation' => array(
							//array('Validation::isNot0', 'AUTO'),
							array('Validation::isLengthBetween', 1, 10, 'AUTO'),
						),
					),
				),
				'type' => array(
					'basic' => array(
						'title' => '类型',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => Mvc::getCcList('nca'),
						'name' => 'type',
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
							'attr' => array('Mvc::converCc', 'nca'),
						),
						'validation' => array(
							array('Validation::isInArray', Mvc::getCcList('nca'), 'AUTO'),
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
							//'attr' => array('qw('-str')->toBlank'),
						),
						'validation' => array(
							array('Validation::isLengthBetween', 1, 40, 'AUTO'),
						),
					),
				),
				'descrip' => array(
					'basic' => array(
						'title' => '描述',
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'descrip',
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
							//'attr' => array('qw('-str')->toBlank'),
						),
						'validation' => array(
							array('Validation::isLengthBetween', 0, 40, 'AUTO'),
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
				'table' => 'nca'
			),
			// 页面显示
			'page' => array(
				'title' => 'NCA',
				'descrip' => 'NCA管理',
				'rowNum' => 10,
			),
		);
	}
}
?>
