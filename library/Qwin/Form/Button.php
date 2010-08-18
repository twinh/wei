<?php
/**
 * QwButton 的名称
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
 * @subpackage  Form
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-08 16:30 中文
 * @todo        缓存类的方法名称
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
    
    function render($set)
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
