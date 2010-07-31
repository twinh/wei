<?php
 /**
 * 菜单
 *
 * 菜单后台模型
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
 * @version   2009-11-21 16:02
 * @since     2009-11-21 16:02
 */

class Admin_Setting_Menu extends Qwin_Miku_Setting
{
	public function defaultMetadata()
	{
		return array(
			// 基本属性
			'field' => array(
				'id' => array(
					'basic' => array(
						'title' => '编号',
                        'order' => 5,
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'hidden',
						'_value' => '',
						'name' => 'id',
					),
					'attr' => array(
						'isUrlQuery' => false,
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
					),
				),
				'category_id' => array(
					'basic' => array(
						'title' => '类别',
                        'order' => 10,
						'descrip' => '只有描述',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => $this->getCategoryList(
                                'admin_menu',
                                array('请选择'),
                                array('id', 'category_id', 'title')
                         ),
						'name' => 'category_id',
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
						'attr' => array(
                            array($this, 'converCategory'),
                            'admin_menu',
                            array('id', 'category_id', 'title')
                        ),
					),
					'validation' => array(
					),
				),
				'title' => array(
					'basic' => array(
						'title' => '名称',
                        'order' => 15,
						'descrip' => array(
							'名称将显示在左栏菜名称将显示在左栏菜单',
						),
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'title',
					),
					'attr' => array(
						'isList' => true,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						//'edit' => array('qw('-str')->secureCode'),
						'show' => '',
						'list' => '',
					),
					'validation' => array(
						'rule' => array(
							'required' => true,
						),
					),
				),
				'url' => array(
					'basic' => array(
						'title' => '地址',
                        'order' => 20,
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '',
						'name' => 'url',
					),
					'attr' => array(
						'isList' => false,
						'isSqlField' => true,
						'is_sql_query' => true,
						'is_search' => false,
					),
					'conversion' => array(
						'add' => '',
						'edit' => '',
						'show' => '',
					),
					'validation' => array(
					),
				),
				'target' => array(
					'basic' => array(
						'title' => '链接目标',
                        'order' => 25,
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'text',
						'_value' => '_self',
						'name' => 'target',
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
						//'attr' => array('qw('-str')->toBlank'),
					),
					'validation' => array(
						'rule' => array(
							'required' => true,
						),
					),
				),
				'is_show' => array(
					'basic' => array(
						'title' => '是否显示',
                        'order' => 30,
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'radio',
						'_value' => '1001001',
						'_resource' => $this->getCommonClassList('yes_or_no'),
						'name' => 'is_show',
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
						'attr' => array(array($this, 'converCommonClass'), 'yes_or_no'),
					),
					'validation' => array(
						/*array('Validation::isInArray', parent::getCcList('yes_or_no'), 'AUTO'),*/
						'rule' => array(
							'required' => true,
						),
					),
				),
				'order' => array(
					'basic' => array(
						'title' => '顺序',
                        'order' => 40,
						'descrip' => array('多个描述', '多个描述'),
					),
					'form' => array(
						'_type' => 'text',
						'_typeExt' => 'datepicker',
						'_icon' => array(
							'file_tree',
							'ajax_upload',
							'fast_copy' => array(
								'from' => '#url'
							),
							'qthumb',
						),
						'_value' => '0',
						'name' => 'order',
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
					),
				),
				'state_code' => array(
					'basic' => array(
						'title' => '状态',
                        'order' => 45,
						'descrip' => '',
					),
					'form' => array(
						'_type' => 'select',
						'_value' => '',
						'_resource' => $this->getCommonClassList('state_code'),
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
						'attr' => array(array($this, 'converCommonClass'), 'state_code'),
					),
					'validation' => array(
						/*array('Validation::isInArray', parent::getCcList('state_code'), 'AUTO'),*/
					),
				),
			),
			// 附加属性
			'fieldExt' => array(
				'key' => 'id',
			),
			// 核心
			'core' => array(
				'table' => 'admin_menu'
			),
			// 页面显示
			'page' => array(
				'title' => '系统菜单',
				'descrip' => '系统菜单管理',
				'rowNum' => 10,
			),
		);
	}
}
