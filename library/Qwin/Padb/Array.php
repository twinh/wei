<?php
/**
 * Array
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
 * @subpackage  Padb
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-27 17:37:22
 */

class Qwin_Padb_Array
{
    /**
     * 根据指定的键名和排列顺序,排列二级数组
     *
     * @param array $list 排列的二级数组
     * @param string $orderKey 排序的键名
     * @param int $type 3 => SORT_DESC; 4 => SORT_ASC;
     * @param array $type 排列好的二级数组
     * @todo 可以指定排列顺序,由小到大和由大到小
     */
    public static function orderBy($list, $orderKey = 'order', $type = SORT_DESC)
    {
        if(empty($list))
        {
            return $list;
        }
        $list2 = array();
        foreach($list as $key => $val)
        {
            $list2[$key] = $val[$orderKey];
        }
        array_multisort($list2, $type, $list);
        return $list;
    }

    public static function decode($input,$t = null){
        $output = '';
        if (is_array($input)) {
            $output .= "array(\r\n";
            foreach ($input as $key => $value) {
                $output .= $t."    ".self::decode($key,$t."    ").' => '.self::decode($value,$t."    ");
                $output .= ",\r\n";
            }
            $output .= $t.')';
        } elseif (is_string($input)) {
            $output .= "'".str_replace(array("\\","'"),array("\\\\","\'"),$input)."'";
        } elseif (is_int($input)) {
            $output .= $input;
        } elseif ( is_double($input)) {
            $output .= "'".(string)$input."'";
        } elseif (is_bool($input)) {
            $output .= $input ? 'true' : 'false';
        } else {
            $output .= 'null';
        }
        return $output;
    }
}
