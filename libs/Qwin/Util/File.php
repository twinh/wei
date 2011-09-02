<?php
/**
 * File
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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

class Qwin_Util_File
{
    /**
     * 系统是否为Windows
     * @var boolen|null
     */
    protected static $_inWin = null;

    /**
     * 将内容以数组的形式写入文件中
     *
     * @param mixed $array 内容
     * @param string $path 路径,如果不存在,会自动创建
     * @param string|null $name 如果存在,则以此作为数组名字
     * @return <type> 
     */
    public static function writeArray($path, $array, $name = null)
    {
        // 创建路径
        self::makePath($path);

        $array = var_export($array, true);
        if ($name) {
            $array = "<?php\n\$$name = $array;";
        } else {
            $array = "<?php\nreturn $array;";
        }
        
        if (!file_put_contents($path, $array)) {
            throw new Qwin_Util_Exception('Can not write into the file "' . $path . '"');
        }
        
        return true;
    }

    /**
     * 写入数组到已知数组的文件中
     * 应注意可能导致读取错误
     *
     * @param string $path 文件路径
     * @param array $array 数组
     * @param array $name 数组写入的键名
     * @return boolen
     */
    public static function appendArray($path, $array, $name = null)
    {
        if (!is_file($path)) {
            return self::writeArray($path, $array, $name);
        }
        $code = null;
        if ($name) {
            $code .= var_export($name, true) . ' => ';
        }
        $code .= var_export($array, true) . ',' . PHP_EOL . ');';

        // 打开文件,并移动指针到倒数第三位倒数几位分别是 "换行符);"
        $fp = fopen($path, 'r+');
        fseek($fp, -3, SEEK_END);
        fwrite($fp, $code);
        fclose($fp);
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

    /**
     * 获取第二个路径对于第一个路径的相对路径
     *
     * @param string $from 第一个路径
     * @param string $to 第二个路径
     * @return string
     * @see http://stackoverflow.com/questions/2637945/getting-relative-path-from-absolute-path-in-php
     */
    public static function getRelativePath($from, $to)
    {
        $from     = explode('/', $from);
        $to       = explode('/', $to);
        $relPath  = $to;

        foreach($from as $depth => $dir) {
            // find first non-matching dir
            if($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
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
     */
    public static function isExist($file)
    {
        if(!is_file($file)) {
            return false;
        }
        // 如果是前面的路径不一致?
        if (self::_inWin() && basename(realpath($file)) != basename($file)) {
            return false;
        }
        return true;
    }

    /**
     * 检测系统是否为Windows
     *
     * @return boolen
     */
    protected static function _inWin()
    {
        if (null !== self::$_inWin) {
            return self::$_inWin;
        }
        self::$_inWin = 'WIN' == substr(PHP_OS, 0, 3) ? true : false;
        return self::$_inWin;
    }

    /**
     * 过滤文件名中的非法字符
     *
     * @param string $name
     * @return string
     */
    public static function filterName($name)
    {
        return str_replace(array('\\', '/', ':', '*', '<', '>', '?', '|'), '', $name);
    }

    /**
     * 转换路径分隔符为网址路径
     * 
     * @param string $path 路径
     * @return string 路径
     */
    public static function toUrlSeparator($path)
    {
        return strtr($path, array('\\' => '/'));
    }

    /**
     * 转换路径为当前系统类型的路径
     *
     * @param string $path 路径
     * @return string 路径
     */
    public static function toPathSeparator($path)
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}
