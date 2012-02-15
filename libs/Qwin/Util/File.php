<?php
/**
 * File
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
