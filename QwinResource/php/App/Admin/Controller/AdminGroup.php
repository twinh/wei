<?php
 /**
 * 用户组
 *
 * 用户组后台控制器,包括显示列表,添加,编辑,设置权限
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
 * @version   2009-11-21 13:17 utf-8 中文
 * @since     2009-11-21 13:17 utf-8 中文
 */

class Admin_Controller_Group extends Qwin_Miku_Controller
{
    /**
     * 列表
     */
    function actionDefault()
    {
        return Qwin_Class::run('Qwin_Action_List');
    }

    /**
     * 添加
     */
    function actionAdd()
    {
        return Qwin_Class::run('Qwin_Action_Add');
    }

    /**
     * 编辑
     */
    function actionEdit()
    {
        return Qwin_Class::run('Qwin_Action_Edit');
    }

    /**
     * 删除
     */
    function actionDelete()
    {
        return Qwin_Class::run('Qwin_Action_Delete');
    }

    /**
     * 列表的 json 数据
     */
    function actionJsonList()
    {
        return Qwin_Class::run('Qwin_Action_JsonList');
    }

    /**
     * 查看
     */
    function actionShow()
    {
        return Qwin_Class::run('Qwin_Action_Show');
    }

    /**
     * 筛选
     */
    function actionFilter()
    {
        return Qwin_Class::run('Qwin_Action_Filter');
    }


    function actionAllocate_permissions()
    {
        if(!$_POST)
        {
            qw('-qry')->setTable($this->__meta['db']['table']);
            $query_arr = array(
                'SELECT' => "`permissions`",
                'WHERE' => "`id`=" . qw('-ini')->g('id'),
            );
            $sql = qw('-qry')->getOne($query_arr);
            $data = qw('-db')->getOne($sql);

            qw('-qry')->setTable('nca');
            $query_arr = array();
            $sql = qw('-qry')->getList($query_arr);
            $list = qw('-db')->getList($sql);

            $this->__view['permissions'] = unserialize($data['permissions']);
            $this->__view['list'] = &$list;
        } else {
            unset($_SESSION['permissions']);
            $permissions = serialize(qw('-ini')->p('nca'));
            $id = qw('-ini')->p('id');
            $sql = "UPDATE " . qw('-qry')->$prefix . $this->__meta['db']['table'] . " SET `permissions` = '$permissions' WHERE `id` = " . $id;
            qw('-db')->Query($sql);
            qw('-url')->to(url(array('admin', $this->__query['controller'])));
        }
    }


    function actionAllocate_menu()
    {
        if(!$_POST)
        {
            $id = qw('-ini')->g('id');
            // 菜单
            $menu_list = $this->getClassList('admin_menu');
            // 菜单权限
             qw('-qry')->setTable('admin_group');
            $query_arr = array(
                'WHERE' => "`id` = " . $id,
            );
            $sql = qw('-qry')->getOne($query_arr);
            $data = qw('-ini')->$db->getOne($sql);
            $this->__view = array(
                'menu_list' => $menu_list,
                'menu_check' => unserialize($data['menu']),
            );
        } else {
            unset($_SESSION['menu']);
            $menu = serialize(qw('-ini')->p('menu'));
            $id = qw('-ini')->p('id');
            $sql = "UPDATE " . qw('-qry')->$prefix . $this->__meta['db']['table'] . " SET `menu` = '$menu' WHERE `id` = " . $id;
            qw('-db')->Query($sql);
            qw('-url')->to(url(array('admin', $this->__query['controller'])));
        }
    }

    function converListOperate($value, $data)
    {
        $return_data = '<a href="' . url(array('admin', 'group', 'allocate_permissions'), array('id' => $data['id'])) . '">权限管理</a>|<a href="' . url(array('admin', 'group', 'allocate_menu'), array('id' => $data['id'])) . '">菜单管理</a>';
        return $return_data;
    }
}
