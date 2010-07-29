<?php
 /**
 * 首页
 *
 * 首页后台模型
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
 * @version   2009-11-21 15:48 utf-8 中文
 * @since     2009-11-21 15:48 utf-8 中文
 */

class Admin_Setting_Default extends Qwin_Miku_Setting
{
    public function defaultMetadata()
    {
        return array(
            // 基本属性
            'field' => array(
                'username' => array(
                    'basic' => array(
                        'title' => '用户名',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_value' => '',
                        'name' => 'username',
                    ),
                    'attr' => array(
                        'isSqlField' => true,
                    ),
                    'conversion' => array(
                        'db' => array('htmlspecialchars'),
                    ),
                    'validation' => array(
                        'rule' => array(
                            'required' => true,
                            'rangelength' => array(3, 16),
                        ),
                    ),
                ),
                'password' => array(
                    'basic' => array(
                        'title' => '密码',
                    ),
                    'form' => array(
                        '_type' => 'password',
                        //'_typeExt' => 'combobox',
                        '_value' => '',
                        '_resource' => array(
                            'gz' => '广州',
                            'sh' => '上海',
                            'bj' => '北京',
                            'hz' => '杭州',
                        ),
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isSqlField' => true,
                    ),
                    'validation' => array(
                        'rule' => array(
                            'required' => true,
                            'rangelength' => array(6, 16),
                        ),
                    ),
                ),
                'verify_code' => array(
                    'basic' => array(
                        'title' => '验证码',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_icon' => array(
                            'captcha',
                        ),
                        '_value' => '',
                        'name' => 'verify_code',
                    ),
                    'attr' => array(
                        'isSqlField' => true,
                    ),
                    'validation' => array(
                        'rule' => array(
                            'required' => true,
                            'digits' => true,
                            // 固定长度
                            'rangelength' => array(5, 5),
                            'isCaptcha' => true,
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
