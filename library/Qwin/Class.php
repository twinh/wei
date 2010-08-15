<?php
/**
 * 类管理器
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
 * @subpackage  Class
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-20 15:21
 * @todo        父类,接口等的类的加载问题
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
    private static $_foundFile = array();
    
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
    private static $_cacheFile;
    
    /**
     * 类名映射,用于简化输入类名
     *
     */
    private static $_classMap = array();

    /**
     * 类库的路径
     * @var string
     */
    public static $libPath;

    /**
     * 设置缓存文件
     * @param string $cacheFile
     * @return bool
     */
    public function setCacheFile($cacheFile)
    {
        self::$_cacheFile = $cacheFile;
        if(file_exists($cacheFile))
        {
            self::$_classCache = require self::$_cacheFile;
            return true;
        }
        return false;
    }
    
    /**
     * 增加类与类缩写的对应关系
     * 
     * @param array/string $key 对应关系的数组/类缩写
     * @param null/string $class_name 当 $key 是数组时,该值为空/类名
     */
    public static function addMap($key, $class_name = null)
    {
        if(is_array($key))
        {
            foreach($key as $real_key => $real_calss_name)
            {
                self::$_classMap[$real_key] = $real_calss_name;
            }
        } else {
            self::$_classMap[$key] = $class_name;
        }
    }

    /**
     * 增加初始的类
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
     * @todo 先后的次序
     */
    public static function run($name, $param = null)
    {
        isset(self::$_classMap[$name]) && $name = self::$_classMap[$name];

        // 因为 php 类名不区分大小写
        //$name = strtolower($name);

        // 已经实例化过
        if(isset(self::$_instanceClass[$name]))
        {
            return self::$_instanceClass[$name];

        // 类存在,或者通过自动加载取得
        } elseif(class_exists($name)) {
             self::$_instanceClass[$name] = new $name($param);
             return self::$_instanceClass[$name];
        
        // 加载文件,实例化
        } elseif(array_key_exists($name, self::$_classCache)) {
            require_once self::$_classCache[$name];
            self::$_instanceClass[$name] = new $name($param);
            return self::$_instanceClass[$name];
        }
        
        // 没有找到
        return null;
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
    public static function addPath($path, $depth = 3)
    {
        if(is_dir($path) && !array_key_exists($path, self::$_path))
        {
            self::$_path[$path] = $depth;
        }
    }

    /**
     * 增加多个路径
     *
     * @param array $pathSet 路径组
     */
    public static function addMultiPath(array $pathSet)
    {
        foreach($pathSet as $key => $depth)
        {
            self::addPath($key, $depth);
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
    public static function update()
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
    private static function _findClassByPath($path, $depth)
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
                            self::$_foundFile[] = $path_tmp;
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
    private static function _getClassByFile($file)
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
            return null;
        }
        /**
         * 配置数组是类和方法
         */
        if(is_array($set[0]))
        {
            /**
             * $convert[0][0]为类名,尝试加载该类
             * @todo 静态调用和动态调用
             */
            if(!is_object($set[0][0]))
            {
                $set[0][0] = self::run($set[0][0]);
            }
            if(!method_exists($set[0][0], $set[0][1]))
            {
                return null;
            }
        /**
         * 配置数组是函数
         */
        } else {
            if(!function_exists($convert[0]))
            {
                return null;
            }
        }
        // 第一个是方法/函数名
        $function = $set[0];
        array_shift($set);
        return call_user_func_array($function, $set);
    }

    public static function isCallable($set)
    {
        if(is_array($set))
        {
            // 静态类
            if(is_string($set[0]))
            {
                self::load($set[0]);
            }
            return method_exists($set[0], $set[1]);
        } elseif(function_exists($set)) {
            return true;
        }
    }

    /**
     * 获取类库的路径
     *
     * @return string 类库路径
     */
    public static function getLibPath()
    {
        if(isset(self::$libPath))
        {
            return self::$libPath;
        }
        self::$libPath = realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR;
        return self::$libPath;
    }

    /**
     * 注册自动加载类
     */
    public static function setAutoload()
    {
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));
        spl_autoload_register(array(self, 'autoload'));
    }

    /**
     * 自动加载类的方法,适用各类按标注方法命名的类库
     *
     * @param string $className
     * @return bool 是否加载了类
     */
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

    /**
     * 调试,输入各静态变量
     */
    public static function debug()
    {
        echo '<pre>';

        echo '<h1>Paths & Depths</h1>';
        print_r(self::$_path);

        echo '<h1>Found Files</h1>';
        print_r(self::$_foundFile);

        echo '<h1>Classes Cache Array</h1>';
        print_r(self::$_classCache);

        echo '<h1>Classes Map</h1>';
        print_r(self::$_classMap);

        echo '</pre>';
    }
}
