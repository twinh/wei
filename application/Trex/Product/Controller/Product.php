<?php
/**
 * Article
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-06-07
 * @since     2010-06-07
 */

class Default_Product_Controller_Product extends Qwin_Trex_Controller
{
    /**
     * 列表
     */
    public function actionDefault()
    {
        return Qwin::run('Qwin_Trex_Action_List');
    }

    /**
     * 添加
     */
    public function actionAdd()
    {
        return Qwin::run('Qwin_Trex_Action_Add');
    }

    /**
     * 编辑
     */
    public function actionEdit()
    {
        return Qwin::run('Qwin_Trex_Action_Edit');
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        return Qwin::run('Qwin_Trex_Action_Delete');
    }

    /**
     * 列表的 json 数据
     */
    public function actionJsonList()
    {
        Qwin::load('Qwin_converter_Time');
        return Qwin::run('Qwin_Trex_Action_JsonList');
    }

    /**
     * 查看
     */
    public function actionShow()
    {
        return Qwin::run('Qwin_Trex_Action_Show');
    }

    public function convertDbDateCreated()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    public function convertDbDateModified()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    public function convertListOperation($val, $name, $data, $cpoyData)
    {
        $html = $this->meta->getOperationLink($this->__meta['db']['primaryKey'], $data[$this->__meta['db']['primaryKey']], $this->_set);
        return $html;
    }

    public function convertDbId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }

    public function convertDbDetailId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }

    public function convertAddOrder()
    {
        $class = Qwin::run('-ini')->getClassName('Model', $this->_set);
        return $this->meta->getInitalOrder($class);
    }

    public function convertDbCategoryId($val, $name, $data)
    {
        '0' == $val && $val = null;
        return $val;
    }

    public function convertListCategoryId($val, $name, $data)
    {
        if(!isset($this->_articleCategory))
        {
            $this->_articleCategory = require QWIN_ROOT_PATH . '/Cache/Php/List/ProductCategory.php';
        }
        if(!isset($this->_articleCategory2))
        {
            $this->_articleCategory2 = array();
            foreach($this->_articleCategory as $row)
            {
                $this->_articleCategory2[$row['id']] = $row['name'];
            }
        }
        return $this->_articleCategory2[$val];
    }

    public function getCategoryIdResource()
    {
        if(!isset($this->_articleCategory))
        {
            $this->_articleCategory = require QWIN_ROOT_PATH . '/Cache/Php/List/ProductCategory.php';
        }
        $data = &$this->_articleCategory;
        foreach($data as $row)
        {
            if(null == $row['parent_id'])
            {
                $category[$row['id']] = $row['name'];
                foreach($data as $row2)
                {
                    if($row2['parent_id'] == $row['id'])
                    {
                        $category[$row2['id']] = '|-' . $row2['name'];
                    }
                }
            }
        }
        return $category;
    }
}
