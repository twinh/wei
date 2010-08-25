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

class Qwin_Helper_File
{    
    public static function writeAsArray($code, $path, $name = '')
    {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        self::makePath($path);

        $code = Qwin::run('Qwin_Helper_Array')->tophpCode($code);
        if('' != $name)
        {
            $fileData = "<?php\r\n\$$name = $code;";
        } else {
            $fileData = "<?php\r\nreturn $code;";
        }
        file_put_contents($path, $fileData);
    }

    public static function makePath($path)
    {
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathTemp = '';
        foreach($pathArray as $path)
        {
            $pathTemp .= $path . DIRECTORY_SEPARATOR;
            if(!is_dir($pathTemp))
            {
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
    function scanPath($path, $callback, $level = 0, $type = 'all')
    {
        $file_arr = scandir($path);
        foreach($file_arr as $val)
        {
            if($val == '.' || $val == '..')
            {
                continue;
            // 目录
            } elseif(true == is_dir($path . DS . $val)) {
                self::_evalScanPathCallBack($callback, $val, $path);
                self::scanPath($path . $val . DS, $callback);
            // 文件
            } else {
                self::_evalScanPathCallBack($callback, $val, $path);
            }
        }        
    }
    
    // 执行 scanPath 方法的回调函数
    // TODO 动态类 array($class, $method) 静态类 array('class', 'method');
    private function _evalScanPathCallBack($callback, $file, $path)
    {
        if(true == is_array($callback))
        {
            if(true == is_object($callback[0]))
            {
                $callback[0] = get_class($callback[0]);
            }
            return eval($callback[0] . '::' . $callback[1] . '("' . $file . '", "' . qw('-str')->decodeEvalVarCode($path) . '");');
        } else {
            return $callback($file, $path);
        }
    }
    
    /**
     * 区分大小写的文件存在判断
     * 
     * @param string $file
     * @see thinkphp://Common/functions.php
     */
    function isExist($file, $case = true) {
        if(is_file($file)) {
            //if(IS_WIN && C('APP_FILE_CASE')) {
                if(basename(realpath($file)) != basename($file))
                    return false;
            //}
            return true;
        }
        return false;
    }

}
