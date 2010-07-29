<?php
/**
 * 后台左栏菜单
 *
 * 后台左栏菜单控制器
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
 * @version   2009-11-21 13:18 utf-8 中文
 * @since     2009-11-21 13:18 utf-8 中文
 * @todo      is_eval 的安全问题
 */


class Admin_Controller_Menu extends Qwin_Miku_Controller
{
    function __construct()
    {
        // 逻辑等..
        //$this->executeStandardAction();
    }

    // action 函数
    // 列表
    function actionDefault()
    {
        return Qwin_Class::run('Qwin_Action_List');
    }

    // 添加
    function actionAdd()
    {
        return Qwin_Class::run('Qwin_Action_Add');
    }

    // 编辑
    function actionEdit()
    {
        return Qwin_Class::run('Qwin_Action_Edit');
    }

    // 删除
    function actionDelete()
    {
        return Qwin_Class::run('Qwin_Action_Edit');
    }

    // json 数据
    function actionJsonList()
    {
        return Qwin_Class::run('Qwin_Action_JsonList');
    }

    // 查看
    function actionShow()
    {
        return Qwin_Class::run('Qwin_Action_Show');
    }

    // 筛选
    function actionFilter()
    {
        return Qwin_Class::run('Qwin_Action_Filter');
    }

    /**
     * 更新缓存
     *
     */
    function onAfterDb()
    {
        $self = Qwin_Class::run('-c');
        $query = Qwin_Class::run('-qry');
        $db = Qwin_Class::run('-db');
        $cache = Qwin_Class::run('Qwin_Cache_List');

        $query_arr = array(
            'ORDER' => '`order` ASC, `id` DESC',
        );
        $sql = $query->getList($query_arr);
        $data = $db->getList($sql);

        /*foreach($data as &$row)
        {
            if('{' == substr($row['url'], 0, 1) && '}' == substr)
            // {"namespace":"Admin","controller":"Default","action":"SystemInfo"}
        }*/

        $cache->writeCache($data, 'admin_menu');
    }
}
