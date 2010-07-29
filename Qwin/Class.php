<?php
/**
 * class 的名称
 *
 * class 的简要介绍
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
 * @version   2010-02-20 15:21 utf-8 中文
 * @since     2010-02-20 15:21 utf-8 中文
 * @todo      父类,接口等的类的加载问题
 */

class Qwin_Class
{
    /**
     * 类存放的路径,键名是路径,值是查找的深度
     *
     * @var array
     */
    private static $_path = array();
    
    /**
     * 查找到的文件
     *
     * @var array
     */
    private static $_found_file = array();
    
    /**
     * 类与文件名存放的数组,对应关系的 class => file
     *
     * @var array
     */
    private static $_classCache = array();
    
    /**
     * 类文件的后缀名
     *
     * @var array
     */
    private static $_fileExt = array('php');
    
    /**
     * 所有实例化的类组成的数组
     *
     * @var array
     */
    private static $_instanceClass = array();
    
    /**
     * 类缓存文件的路径
     *
     * @var string
     */
    private static $_cacheFile = '';
    
    /**
     * 类名映射,用于简化输入类名
     *
     */
    private static $_class_map = array();

    /**
     * 类库的路径
     * @var string
     */
    public static $libPath;
    
    /**
     * 初始化类缓存文件路径
     * 
     * @param string/null $cacheFile 类缓存文件路径
     */
    function init($cacheFile = NULL)
    {
        // 默认加入 3 个路径
        self::$_path = array(
            QWIN_PATH => 10,
            RESOURCE_PATH . DS . 'php' => 10,
            ROOT_PATH => 5,
        );
        if(NULL != $cacheFile && file_exists($cacheFile))
        {
            self::$_cacheFile = $cacheFile;
        } else {
            self::$_cacheFile = ROOT_PATH . DS . 'Cache/Php/System/class.php';
        }
        self::$_classCache = require self::$_cacheFile;
    }
    
    
    
    /**
     * 增加类与类缩写的对应关系
     * 
     * @param array/string $key 对应关系的数组/类缩写
     * @param null/string $class_name 当 $key 是数组时,该值为空/类名
     */
    public static function addMap($key, $class_name = NULL)
    {
        if(is_array($key))
        {
            foreach($key as $real_key => $real_calss_name)
            {
                self::$_class_map[$real_key] = $real_calss_name;
            }
        } else {
            self::$_class_map[$key] = $class_name;
        }
    }

    /**
     * 
     * 
     * @param unknown_type $name
     * @param unknown_type $class
     * @return unknown_type
     */
    public static function addClass($name, &$class)
    {
        self::$_instanceClass[$name] = $class;
    }
    
    /**
     * 获得一个类的实例化
     *
     * @todo 初始化时多参数的实现
     */
    public static function run($name, $param = array())
    {
        isset(self::$_class_map[$name]) && $name = self::$_class_map[$name];
        // 因为 php 类名不区分大小写
        //$name = strtolower($name);
        // 已经实例化过
        if(isset(self::$_instanceClass[$name]))
        {
            return self::$_instanceClass[$name];
        // 加载文件,实例化
        } elseif(array_key_exists($name, self::$_classCache)) {
            require_once self::$_classCache[$name];
            self::$_instanceClass[$name] = new $name($param);
            return self::$_instanceClass[$name];
        }
        // 没有找到
        return NULL;
    }
    
    /**
     * 加载类文件,不初始化
     * 
     * @param $class_name
     * @return unknown_type
     */
    public static function load($class_name)
    {
        if(isset(self::$_classCache[$class_name]))
        {
            require_once self::$_classCache[$class_name];
            return true;
        }
        return false;
    }
    
    /**
     * 增加类文件的后缀
     *
     * @param string $ext 文件后缀名,不带"."
     * @return object $this
     */
    public function addExt($ext)
    {
        if(!in_array($ext, self::$_fileExt))
        {
            self::$_fileExt[] = $ext;
        }
    }
    
    /**
     * 删除类文件后缀
     *
     * @param string $ext 文件后缀名,不带"."
     * @return object $this
     */
    public function delExt($ext)
    {
        if(in_array($ext, self::$_fileExt))
        {
            $tmp_ext = array_flip(self::$_fileExt);
            unset(self::$_fileExt[$tmp_ext[$ext]]);
        }
    }
    
    /**
     * 增加路径
     * 
     * @param string $path
     * @param int $depth 查找的深度
     * @retutn object $this
     */
    public function addPath($path, $depth = 3)
    {
        if(is_dir($path) && !array_key_exists($path, self::$_path))
        {
            self::$_path[$path] = $depth;
        }
    }
    
    /**
     * 删除路径
     *
     * @param string $path
     */
    public function delPath($path)
    {
        if(array_key_exists($path, self::$_path))
        {
            unset(self::$_path[$path]);
        }
    }
        
    /**
     *  更新类的缓存文件
     *
     * @todo QInit, QFile 的加载问题
     */
    public function update()
    {
        self::$_classCache = array();
        foreach(self::$_path as $path => $depth)
        {
            self::_findClassByPath($path, $depth);
        }
        self::run('Qwin_Helper_File')->writeArr(self::$_classCache, self::$_cacheFile);
    }
    
    /**
     * 在指定目录下查找类
     *
     * @param string $path 文件路径
     * @param int $depth 查找的深度,即多少层文件夹
     */
    private function _findClassByPath($path, $depth)
    {
        if($depth == 0)
            return false;
        $file_arr = scandir($path);
        foreach($file_arr as $key => $val)
        {
            switch($val)
            {
                case '.' :
                case '..' :
                    continue;
                default :
                    $path_tmp = $path . DS . $val;
                    if(!is_dir($path_tmp))
                    {
                        $path_info = pathinfo($path_tmp);
                        if(isset($path_info['extension']) && in_array($path_info['extension'], self::$_fileExt))
                        {
                            self::$_found_file[] = $path_tmp;
                            self::_getClassByFile($path_tmp);
                        }                        
                    } else {
                        self::_findClassByPath($path_tmp, $depth - 1);
                    }
            }            
        }
    }
    
    /**
     * 在文件中,利用正则取出类名
     *
     * @param string $file 文件路径
     * @todo 对于加密的文件
     */
    private function _getClassByFile($file)
    {
        $data = file_get_contents($file);
        preg_match_all ("/class\s(\w+)[\s|\n]*[\w|\s]*{/i", $data, $class_arr);
        if(0 != count($class_arr[1]))
        {
            foreach($class_arr[1] as $class)
            {
                self::$_classCache[$class] = $file;
                //self::$_classCache[strtolower($class)] = $file;
            }
        }
    }

    /**
     *
     * @param array $set
     */
    public static function callByArray($set)
    {
        if(!is_array($set) || empty($set))
        {
            return NULL;
        }
        /**
         * 配置数组是类和方法
         */
        if(is_array($set[0]))
        {
            /**
             * $conver[0][0]为类名,尝试加载该类
             * @todo 静态调用和动态调用
             */
            if(!is_object($set[0][0]))
            {
                $set[0][0] = self::run($set[0][0]);
            }
            if(!method_exists($set[0][0], $set[0][1]))
            {
                return NULL;
            }
        /**
         * 配置数组是函数
         */
        } else {
            if(!function_exists($conver[0]))
            {
                return NULL;
            }
        }
        // 第一个是方法/函数名
        $function = $set[0];
        array_shift($set);
        return call_user_func_array($function, $set);
    }    

    public static function getLibPath()
    {
        if(!isset(self::$libPath))
        {
            self::$libPath = realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR;
        }
        return self::$libPath;
    }

    public static function autoload($className)
    {
       $classPath = self::getLibPath() . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
       if(file_exists($classPath))
       {
           require $classPath;
           return true;
       }
       return false;
    }
}