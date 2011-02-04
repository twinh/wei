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
 * @subpackage  Util
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Util_Array
{
    /**
     * 将回调函数作用到给定数组的各级单元上,功能同array_map
     *
     * @param array|string $callback 回调方法或函数
     * @param array $array
     * @return array
     * @todo 支持多数组
     */
    public static function multiMap($callback, array $array)
    {
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $array[$key] = call_user_func_array($callback, array($value));
            } else {
                $array[$key] = self::multiMap($callback, $array[$key]);
            }
        }
        return $array;
    }

    // 转换为 js 对象
    public static function toJsObject($qData, $t = null)
    {
        $cData = '';
        if(is_array($qData))
        {
            $iCount = count($qData);
            $i = 1;
            $cData .= "{";
            foreach($qData as $key => $val)
            {
                $cData .= self::toJsObject($key) . ":" . self::toJsObject($val);
                $i != $iCount && $cData .= ",";
                $i++;
            }
            $cData .= "}";
        } elseif(is_string($qData)) {
            $cData .= "'" . str_replace(array("\\","'"),array("\\\\","\'"),$qData) . "'";
        } elseif(is_int($qData)) {
            $cData .= $qData;
        } elseif(is_numeric($qData)) {
            $cData .= "'" . $qData . "'";
        } elseif(is_bool($qData)) {
            $cData .= $qData ? 'true' : 'false';
        } else {
            $cData .= 'null';
        }
        return $cData;
    }

    /**
     * 删除二维数组中的指定键名
     *
     * @param array $data array 二维数组,一般是从数据库读出
     * @param array $key_arr array 删除的键名
     */
    public static function unsetKeyInDyadicArray($data, $key_arr)
    {
        $data2 = array();

        $key_arr = array_flip($key_arr);
        foreach($data as $key => $val)
        {
            $data2[$key] = array_diff_key($val, $key_arr);
        }

        return $data2;
    }

    /**
     *
     * @todo rename
     */
    public static function getArrayByKeyArray($data, $key_arr)
    {
        $data_2 = array();

        $key_arr = array_flip($key_arr);
        foreach($data as $key => $val)
        {
            $data_2[$key] = array_intersect_key($val, $key_arr);
        }
        return $data_2;
    }

    // 提供需要的键名..

    /**
     * 判断一数组是不是二数组的子集
     *
     * @param array $arr_1
     * @param array $arr_2
     * @param string $type
     * @return bool
     * @todo 按键名比较,不按键名比较的几种方式
     */
    public static function isSubset($arr_1, $arr_2)
    {
        return array_intersect_assoc($arr_1, $arr_2) == $arr_1;
    }

    /**
     * 根据指定的键名和排列顺序,排列二级数组
     *
     * @param array $list 排列的二级数组
     * @param string $order_key 排序的键名
     * @param int $type 3 => SORT_DESC; 4 => SORT_ASC;
     * @param array $type 排列好的二级数组
     * @todo 可以指定排列顺序,由小到大和由大到小
     */
    public static function orderBy($list, $order_key = 'order', $type = 4)
    {
        if(0 == count($list))
        {
            return $list;
        }
        $list2 = array();
        foreach($list as $key => $val)
        {
            $list2[$key] = $val[$order_key];
        }
        array_multisort($list2, $type, $list);
        return $list;
    }

    /**
     * 对变量进行 JSON 编码
     *
     * @see http://gggeek.altervista.org/sw/article_20061113.html
     */
    public static function jsonEncode($data)
    {
        require_once 'fastjson.php';
        return FastJSON::encode($data);
    }

    /**
     *
     *
     */
    public static function jsonDecode($data, $type = 'pear')
    {
        switch($type)
        {
            case 'php' :
                if(function_exists('json_decode'))
                {
                    $data = json_decode($data);
                    break;
                }
            case 'pear' :
                require_once 'services_json.php';
                $value = new Services_JSON();
                $data = $value->decode($data);
                break;
            case 'fast' :
            default :
                require_once 'fastjson.php';
                $value = new FastJSON();
                $data = $value->decode($data);
                break;
        }
        return $data;
    }

    public static function getNextKey($name, $data)
    {
        foreach($data as $key => $val)
        {
            $next = $key;
            if($is_break)
            {
                break;
            }
            if($key == $name)
            {
                $is_break = true;
            }
        }
        if($is_break && $next != $name)
        {
            return $next;
        }
        return null;
    }

    public static function getPrevKey($name, $data)
    {
        foreach($data as $key => $val)
        {
            if($key == $name)
            {
                $is_break = true;
                break;
            }
            $prev = $key;
        }
        if($is_break)
        {
            return $prev;
        }
        return null;
    }

    /**
     * 按大写字母分割字符串
     *
     */
    public static function explodeByUppercase($data)
    {
        $arr = array();
        $len = strlen($data);
        $j = 0;
        for($i = 0;$i < $len;$i++)
        {
            // 该字母时大写
            if(strtoupper($data[$i]) == $data[$i])
            {
                $j++;
            }
            $arr[$j] .= $data[$i];
        }
        return $arr;
    }

    /**
     * 强制提供值作为数组的一项
     * 
     * @param mixed $value 提供的值
     * @param array $array 数组
     * @return mixed
     */
    public static function forceInArray($value, array $array)
    {
        !in_array($value, $array) && $value = $array[key($array)];
        return $value;
    }

    /*
    $arr = array(
        'datepicker',
        'colorpicker' => array(
            'width' => 200,
            'height' => 100,
        )
    );
    =>
    $arr = array(
        'colorpicker' => array(
            'width' => 200,
            'height' => 100,
        ),
        'datepicker' => '',
    );
    */
    /**
     * 解码数组,统一数组键名不为数字,方便调用
     */
    public static function decodeArray($arr)
    {
        self::set($arr);
        foreach($arr as $key => $val)
        {
            if(is_numeric($key))
            {
                $arr[$val] = '';
                unset($arr[$key]);
            }
        }
        return $arr;
    }

    /**
     * 为数组键名增加前后缀
     *
     * @param array $arr 数组
     * @param string $fix 前缀或后缀
     * @param string prefix 或者 suffix 或其他,表示前后缀
     * @param array 转换了的数组
     */
    public static function extendKey($arr, $fix, $type = 'prefix')
    {
        if($type == 'prefix')
        {
            $func_return = '"' . $fix . '".$val';
        } else {
            $func_return = '$val."' . $fix . '"';
        }
        $func = create_function('$val', 'return ' . $func_return . ';');
        // 取出键名,对键名进行递归
        $key_arr = array_keys($arr);
        $key_arr = array_map($func, $key_arr);
        return array_combine($key_arr, $arr);
    }

    /**
     * 转换成一维数组
     * @param <type> $arr
     * @return <type>
     */
    public static function multiToSingle($arr)
    {
        $newArr = array();
        foreach($arr as $key => $val)
        {
            if(!is_array($val))
            {
                $newArr[$key] = $val;
            } else {
                foreach($val as $key2 => $val2)
                {
                    $newArr[$key . '_' . $key2] = $val2;
                }
            }
        }
        return $newArr;
    }

     /**
     * 计算两个数组的交集,键名来自第一个数组,值来自第二个数组
     *
     * @param array $array1 第一个参数数组
     * @param array $array2
     * @return array
     */
    public static function intersect($array1, $array2)
    {
        foreach($array1 as $key)
        {
            if(isset($array2[$key]))
            {
                $array1[$key] = $array2[$key];
            } else {
                $array1[$key] = null;
            }
        }
        return $array1;
    }
}
