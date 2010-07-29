<?php
/**
 * Setting
 *
 * Copyright (c) 2009-2010 Twin Huang. All rights reserved.
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
 * @author    Twin Huang <Twin Huang>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   ${date}
 * @since     ${date} ${file_encoding_sign}
 */

class ${namespace}_Controller_${controller} extends Qwin_Miku_Controller
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
        Qwin_Class::load('Qwin_Converter_Time');
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
}
