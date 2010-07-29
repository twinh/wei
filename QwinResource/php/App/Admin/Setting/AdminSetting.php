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
 * @version   2009-11-21 16:18 utf-8 中文
 * @since     2009-11-21 16:18 utf-8 中文
 */

class Admin_Setting_Setting extends Qwin_Miku_Setting
{
    public function defaultMetadata()
    {
        return array(
            // 基本属性
            'field' => array(
                'basic' => array(
                    'basic' => array(
                        'title' => '<strong>基本信息</strong>',
                        'order' => 5,
                    ),
                    'form' => array(
                        '_type' => 'none',
                        '_value' => '',
                        'name' => 'basic',
                    ),
                ),
                'basic_title' => array(
                    'basic' => array(
                        'title' => '名称',
                        'order' => 10,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'basic:title',
                    ),
                ),
                'basic_descrip' => array(
                    'basic' => array(
                        'title' => '描述',
                        'descrip' => '多个描述请换行',
                        'order' => 15,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                        '_value' => '',
                        'name' => 'basic:descrip',
                    ),
                ),
                'basic_order' => array(
                    'basic' => array(
                        'title' => '排列顺序',
                        'order' => 20,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'basic:order',
                    ),
                ),
                'basic_group' => array(
                    'basic' => array(
                        'title' => '分组',
                        'order' => 20,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'basic:group',
                    ),
                ),
                'form' => array(
                    'basic' => array(
                        'title' => '<strong>表单属性</strong>',
                        'order' => 25,
                    ),
                    'form' => array(
                        '_type' => 'none',
                        '_value' => '',
                        'name' => 'form',
                    ),
                ),
                'form__type' => array(
                    'basic' => array(
                        'title' => '表单类型',
                        'order' => 30,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => $this->getCommonClassList('form_type'),
                        '_value' => 1002002,
                        'name' => 'form:_type',
                    ),
                ),
                'form__typeExt' => array(
                    'basic' => array(
                        'title' => '表单附加类型',
                        'order' => 35,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'form:_typeExt',
                    ),
                ),
                'form__value' => array(
                    'basic' => array(
                        'title' => '初始值',
                        'order' => 40,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'form:_value',
                    ),
                ),
                'form_name' => array(
                    'basic' => array(
                        'title' => '名称',
                        'order' => 45,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'form:name',
                    ),
                ),
                /*'form__resource' => array(
                    'basic' => array(
                        'title' => '资源',
                        'order' => 45,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'form__resource',
                    ),
                    'validation' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),*/
                'attr' => array(
                    'basic' => array(
                        'title' => '<strong>列表信息</strong>',
                        'order' => 50,
                    ),
                    'form' => array(
                        '_type' => 'none',
                        '_value' => '',
                        'name' => 'list',
                    ),
                ),
                'list_isUrlQuery' => array(
                    'basic' => array(
                        'title' => '通过Url查询',
                        'order' => 55,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'list:isUrlQuery',
                    ),
                ),
                'list_isList' => array(
                    'basic' => array(
                        'title' => '显示在列表',
                        'order' => 60,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'list:isList',
                    ),
                ),
                'list_isSqlField' => array(
                    'basic' => array(
                        'title' => '数据库字段',
                        'order' => 65,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'list:isSqlField',
                    ),
                ),
                'list_is_sql_query' => array(
                    'basic' => array(
                        'title' => '允许sql查询',
                        'order' => 70,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'list:is_sql_query',
                    ),
                ),
                /*'validation' => array(
                    'basic' => array(
                        'title' => '<strong>验证</strong>',
                        'order' => 75,
                    ),
                    'form' => array(
                        '_type' => 'none',
                        '_value' => '',
                        'name' => 'validation',
                    ),
                ),
                'validation_rule' => array(
                    'basic' => array(
                        'title' => '验证规则',
                        'order' => 75,
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'validation_rule',
                    ),
                ),*/
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
                'title' => '配置',
                'rowNum' => 10,
            ),
        );
    }

    public function controllerSetting()
    {
        return array(
            'namespace' => array(
                'basic' => array(
                    'title' => 'Namesapce',
                    'order' => -5,
                    'group' => 'Controller Info'
                ),
                'form' => array(
                    '_type' => 'select',
                    '_resource' => $this->getNamespace(),
                    '_value' => '',
                    'name' => 'namespace',
                ),
            ),
            'controller' => array(
                'basic' => array(
                    'title' => 'Controller',
                    'order' => 5,
                    'group' => 'Controller Info'
                ),
                'form' => array(
                    '_type' => 'text',
                    '_value' => $_GET['table'],
                    'name' => 'controller',
                ),
            ),
            'title' => array(
                'basic' => array(
                    'title' => 'Title',
                    'order' => 10,
                    'group' => 'Controller Info'
                ),
                'form' => array(
                    '_type' => 'text',
                    '_value' => $_GET['table'],
                    'name' => 'title',
                ),
            ),
            'table' => array(
                'basic' => array(
                    'title' => 'Table',
                    'order' => 15,
                    'group' => 'Controller Info'
                ),
                'form' => array(
                    '_type' => 'text',
                    '_value' => $_GET['table'],
                    'name' => 'table',
                ),
            ),
        );
    }

    public function tableSetting()
    {
        return array(
            'field' => array(
                '' => array(
                    'table' => array(
                        'basic' => array(
                            'title' => 'Table',
                        ),
                        'form' => array(
                            '_type' => 'select',
                            '_value' => '',
                            '_resource' => '',
                            'name' => 'table',
                        ),
                    ),
                ),
            ),
            'page' => array(
                'title' => '',
            ),
        );
    }
}
