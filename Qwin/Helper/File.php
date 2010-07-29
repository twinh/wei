<?php
/**
 * qfile 的名称
 *
 * qfile 的简要介绍
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
 * @version   2009-05-10 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 */

class Qwin_Helper_File
{    
    // 写入文件
    function write($filename,$data,$method='rb+',$iflock=1,$check=0,$chmod=1){
        $check && strpos($filename,'..')!==false && exit('Forbidden');
        touch($filename);
        $handle = fopen($filename,$method);
        $iflock && flock($handle,LOCK_EX);
        fwrite($handle,$data);
        $method=='rb+' && ftruncate($handle,strlen($data));
        fclose($handle);
        $chmod && @chmod($filename,0777);
    }
    
    function writeArr($arr, $path, $name = '')
    {
        $arr = Qwin_Class::run('Qwin_Helper_Array')->tophpCode($arr);
        if('' != $name)
        {
            $file_str = "<?php\r\n\$$name = $arr;\r\n?>";
        } else {
            $file_str = "<?php\r\nreturn $arr;\r\n ?>";
        }
        self::write($path, $file_str);
    }

    function read($filename,$method='rb'){
        //strpos($filename,'..')!==false && exit('Forbidden');
        $filedata = '';
        if ($handle = @fopen($filename,$method)) {
            flock($handle,LOCK_SH);
            $filedata = @fread($handle,filesize($filename));
            fclose($handle);
        }
        return $filedata;
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
