<?php
 /**
 * jqgrid
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
 * @version   2010-02-01 13:30 utf-8 中文
 * @since     2010-02-01 13:30 utf-8 中文
 */
class Qwin_JQuery_JqGrid
{
    private $_set;
    
    function __construct($set)
    {
        $this->setSet($set);
    }
    
    
    function setSet($set)
    {
        $this->_set = $set;
    }
    
    // 获取 url 参数
    public function getJsonUrl()
    {;
        $url_arr = Qwin_Class::run('-url')->getGetArray();
        $url_arr['action'] = 'JsonList';
        $url = '?' . Qwin_Class::run('-url')->arrayKey2Url($url_arr);
        return $url;
    }
    
    public function getColData()
    {
        $col_setting_arr = array();
        $col_name_arr = array();
        foreach($this->_set['field'] as $val)
        { 
            if($val['list']['isList'] == true)
            {
                $col_setting_arr[] = array(
                    'name' => $val['form']['name'],
                    'index' => $val['form']['name'],
                );
                if($this->_set['db']['primaryKey'] == $val['form']['name'])
                {
                    $col_setting_arr[count($col_setting_arr) - 1]['hidden'] = true;
                }
                $col_name_arr[] = $val['basic']['title'];
            }
        }
        return array(
            'col_setting' => &$col_setting_arr,
            'col_name' => &$col_name_arr,
        );
    }
}
