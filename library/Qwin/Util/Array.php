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

    /**
     * 删除数组中指定的键名
     *
     * @param array $data 二维数组,一般是从数据库读出
     * @param array $keyList 删除的键名
     * @return array
     */
    public static function unsetByKey(array $data, array $keyList)
    {
        $temp = array();
        $keyList = array_flip($keyList);
        foreach($data as $key => $value) {
            $temp[$key] = array_diff_key($value, $keyList);
        }
        return $temp;
    }

    /**
     * 获取由指定的键名组成的数组
     *
     * @param array $data 二维数组,一般是从数据库读出
     * @param array $keyList 指定的键名
     * @return array
     */
    public static function filterByKey(array $data, array $keyList)
    {
        $temp = array();
        $keyList = array_flip($keyList);
        foreach($data as $key => $value) {
            $temp[$key] = array_intersect_key($value, $keyList);
        }
        return $temp;
    }

    /**
     * 判断数组1是不是数组2的子集
     *
     * @param array $array1 数组1
     * @param array $array2 数组2
     * @param string $type
     * @return bool
     * @todo 按键名比较,不按键名比较的几种方式
     */
    public static function isSubset($array1, $array2)
    {
        return array_intersect_assoc($array1, $array2) == $array1;
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
    public static function orderBy($list, $keyName = 'order', $type = SORT_DESC)
    {
        if (0 == count($list)) {
            return $list;
        }
        $list2 = array();
        foreach ($list as $key => $value) {
            $list2[$key] = $val[$keyName];
        }
        array_multisort($list2, $type, $list);
        return $list;
    }

    /**
     * Json 编码
     * @param mixed $data
     * @return mixed
     */
    public static function jsonEncode($data)
    {
        return json_encode($data);
        require_once 'fastjson.php';
        return FastJSON::encode($data);
    }

    /**
     * Json 解码
     * 
     * @param string $data
     * @return mixed
     */
    public static function jsonDecode($data)
    {
        require_once 'services_json.php';
        $value = new Services_JSON();
        return $value->decode($data);
    }

    /**
     * 按大写字母分割字符串
     *
     * @param string $data 字符串
     * @return array
     */
    public static function explodeByUpper($data)
    {
        return preg_split('/(?<!^)(?=[A-Z])/', (string)$data);
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

    /**
     * 获取数组下一个键名
     *
     * @param string|int $searchKey 当前键名
     * @param array $array 数组
     * @return string|int 键名
     * @see http://www.webmasterworld.com/php/3321169.htm
     */
    public static function getNextKey($searchKey, $array)
    {
        $nextKey = false;
        $isFound = false;
        foreach($array as $key => $value) {
            if ($isFound) {
                $nextKey = $key;
                break;
            }
            if ($key == $searchKey) {
                $isFound = true;
            }
        }
        return $nextKey;
    }

    /**
     * 获取数组上一个键名
     *
     * @param string|int $searchKey 当前键名
     * @param array $array 数组
     * @return string|int 键名
     */
    public static function getPrevKey($searchKey, $array)
    {
        $prevKey = false;
        $isFound = false;
        foreach($array as $key => $value) {
            if ($isFound) {
                break;
            }
            if ($key == $searchKey) {
                $isFound = true;
            } else {
                $prevKey = $key;
            }
        }
        return $prevKey;
    }

    /**
     * 解码数组,统一数组键名不为数字,方便调用
     *
     * @param string $array
     * @return array
     * @example $array = array(
     *              'datepicker',
     *              'colorpicker' => array(
     *                  'width' => 200,
     *                  'height' => 100,
     *              )
     *          );
     *          => $array = array(
     *              'datepicker' => array(
     *              ),
     *              'colorpicker' => array(
     *                  'width' => 200,
     *                  'height' => 100,
     *              )
     *          );
     */
    public static function decodeArray($array)
    {
        foreach($array as $key => $val) {
            if (is_numeric($key)) {
                $array[$val] = array();
                unset($array[$key]);
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
        if ($type == 'prefix') {
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
     * 计算两个数组的交集,键名来自第一个数组,值来自第二个数组
     *
     * @param array $array1 第一个参数数组
     * @param array $array2
     * @return array
     */
    public static function intersect($array1, $array2)
    {
        foreach ($array1 as $key) {
            if (isset($array2[$key])) {
                $array1[$key] = $array2[$key];
            } else {
                $array1[$key] = null;
            }
        }
        return $array1;
    }
}
