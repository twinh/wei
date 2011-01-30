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
    private static $_instancedClass = array();
    
    /**
     * 类缓存文件的路径
     *
     * @var string
     */
    private static $_cacheFile;

    protected static $_autoloadPath = array();

    /**
     * 设置缓存文件
     * @param string $cacheFile
     * @return bool
     */
    public static function setCacheFile($cacheFile)
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
     * 增加初始的类
     * 
     * @param string $name
     * @param object $class
     * @return unknown_type
     */
    public static function addClass($name, $class)
    {
        $name = strtolower($name);
        self::$_instancedClass[$name] = $class;
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
            $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
            self::_findClassByPath($path, $depth);
        }
        $fileContent = "<?php\r\nreturn " . var_export(self::$_classCache, true) . ";\r\n ?>";
        file_put_contents(self::$_cacheFile, $fileContent);
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
        if(0 != count($class_arr[1])) {
            foreach($class_arr[1] as $class) {
                //self::$_classCache[$class] = $file;
                self::$_classCache[strtolower($class)] = $file;
            }
        }
    }

    /**
     *
     * @param array $set
     * @param mixed 如果不可调用时,将返回该值
     */
    public static function callByArray($set, $value = null)
    {
        if(!is_array($set) || empty($set))
        {
            return $value;
        }
        /**
         * 配置数组是类和方法
         */
        if(is_array($set[0]) && isset($set[0][0]) && isset($set[0][1]))
        {
            /**
             * $filter[0][0]为类名,尝试加载该类
             * @todo 静态调用和动态调用
             */
            if(!is_object($set[0][0]))
            {
                $set[0][0] = Qwin::run($set[0][0]);
            }
            if(!method_exists($set[0][0], $set[0][1]))
            {
                return $value;
            }
        /**
         * 配置数组是函数
         */
        } elseif(is_string($set[0])) {
            if(!function_exists($set[0]))
            {
                return $value;
            }
        /**
         * 无法通过解析
         */
        } else {
            return $value;
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
     * 注册自动加载类
     *
     * @param array|string $pathList 自动加载的初始路径
     */
    public static function setAutoload($pathList = null)
    {
        // 设置自动加载的路径
        if (!is_array($pathList)) {
            $pathList[] = $pathList;
        }
        $pathList[] = dirname(dirname(__FILE__));
        foreach ($pathList as $path) {
            self::$_autoloadPath[] = realpath($path) . DIRECTORY_SEPARATOR;
        }
        self::$_autoloadPath = array_unique(self::$_autoloadPath);

        // 将类库加入加载路径中
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));
        spl_autoload_register(array('self', 'autoload'));
    }

    /**
     * 自动加载类的方法,适用各类按标注方法命名的类库
     *
     * @param string $className
     * @return bool 是否加载了类
     */
    public static function autoload($className)
    {
        foreach(self::$_autoloadPath as $path)
        {
            $path = $path . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            if (file_exists($path)) {
                require $path;
                return true;
            }
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
