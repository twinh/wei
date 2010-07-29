<?php
/**
 * QwButton 的名称
 *
 * QwButton 的简要介绍
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
 * @version   2010-02-08 16:30 中文
 * @since     2010-02-08 16:30 中文
 * @todo      缓存类的方法名称
 */

class Qwin_Form_Button
{
    // 类集合
    private static $_class = array();
        
    /**
     * 增加扩展类
     */
    public function add($class_name)
    {
        //if(class_exists($class_name) && !isset(self::$_class[$class_name]))
        //{
            self::$_class[$class_name] = $class_name;
        //}
    }
    
    function auto($set)
    {
        if(!isset($set['_icon']))
        {
            return '';
        }
        $data = '';
        $type_arr = qw('-arr')->decodeArray($set['_icon']);
        foreach($type_arr as $method => $type_set)
        {
            $method = str_replace('_', '', $method);
            foreach(self::$_class as $class)
            {
                if(method_exists(qw($class), $method))
                {
                    $data .= call_user_func_array(
                        array(qw($class), $method),
                        array($set, $type_set)
                    );
                }
            }
        }
        return $data;
    }
    
    /**
     * 根据函数名称加载视图
     * 
     * @param string $function_name
     * @return unknown_type
     */
    public function loadView($function_name)
    {
        
    }
}
