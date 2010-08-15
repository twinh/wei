<?php
 /**
 * jqgrid
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Qwin
 * @subpackage  JQuery
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-01 13:30
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
        $url_arr = Qwin::run('-url')->getGetArray();
        $url_arr['action'] = 'JsonList';
        $url = '?' . Qwin::run('-url')->arrayKey2Url($url_arr);
        return $url;
    }
    
    public function getColData()
    {
        $col_setting_arr = array();
        $col_name_arr = array();
        foreach($this->_set['field'] as $val)
        { 
            if($val['attr']['isList'] == true)
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
