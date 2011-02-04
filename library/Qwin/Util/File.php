<?php
/**
 * File
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
 * @subpackage  Helper
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

/**
 * 确定系统类型
 */
if (!defined('IN_WIN')) {
    if (substr(PHP_OS, 0, 3) == 'WIN') {
        defiend('IN_WIN', true);
    } else {
        defiend('IN_WIN', false);
    }
}

class Qwin_Util_File
{
    /**
     * 将内容以数组的形式写入文件中
     *
     * @param mixed $code 内容
     * @param string $path 路径,如果不存在,会自动创建
     * @param string|null $name 如果存在,则以此作为数组名字
     * @return <type> 
     */
    public static function writeAsArray($code, $path, $name = null)
    {
        // 创建路径
        self::makePath($path);

        $code = var_export($code, true);
        if (!$name) {
            $code = "<?php\n\$$name = $code;";
        } else {
            $code = "<?php\nreturn $code;";
        }
        
        if (!file_put_contents($path, $code)) {
            throw new Qwin_Util_Exception('Can not write into the file "' . $path . '"');
        }
        
        return true;
    }

    /**
     * 创建路径
     *
     * @param string $path 路径
     */
    public static function makePath($path)
    {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathTemp = '';
        foreach ($pathArray as $path) {
            $pathTemp .= $path . DIRECTORY_SEPARATOR;
            if (!is_dir($pathTemp)) {
                mkdir($pathTemp);
            }
        }
    }
    
    /*
    * 从指定的路径中读取文件,或文件夹, 使用回调函数,对文件进行处理
    * @param $path string 起始的路径
    * @param $callback mixed 回调函数,包括一般函数,类,静态类, 包含2个参数,依次是 文件(夹)名称, 文件路径
    * @param $level int 查找路径的文件夹层数, 0 表示所有, 1 表示当前层, 2 表示到第二层,依次类推
    * @param $type string 回调函数作用的范围,包括 all, dir, file
    * @todo 限定数目等
    */
    /*function scanPath($path, $callback, $level = 0, $type = 'all')
    {
        $file_arr = scandir($path);
        foreach ($file_arr as $val) {
            if ($val == '.' || $val == '..') {
                continue;
            // 目录
            } elseif (true == is_dir($path . DS . $val)) {
                self::_evalScanPathCallBack($callback, $val, $path);
                self::scanPath($path . $val . DS, $callback);
            // 文件
            } else {
                self::_evalScanPathCallBack($callback, $val, $path);
            }
        }        
    }*/
    
    // 执行 scanPath 方法的回调函数
    // TODO 动态类 array($class, $method) 静态类 array('class', 'method');
    /*private function _evalScanPathCallBack($callback, $file, $path)
    {
        if (true == is_array($callback)) {
            if (true == is_object($callback[0])) {
                $callback[0] = get_class($callback[0]);
            }
            return eval($callback[0] . '::' . $callback[1] . '("' . $file . '", "' . qw('-str')->decodeEvalVarCode($path) . '");');
        } else {
            return $callback($file, $path);
        }
    }*/
    
    /**
     * 判断文件是否存在,区分名称大小写
     * 
     * @param string $file
     * @see thinkphp://Common/functions.php
     */
    public function isExist($file, $case = true) {
        if(is_file($file)) {
            if (IN_WIN) {
                if(basename(realpath($file)) != basename($file))
                    return false;
            }
            return true;
        }
        return false;
    }
}
