<?php
/**
 * Qwin框架核心类,使用注册模式,统一管理全部资源(类对象,数组配置等等),包含的特性如下
 *  1. 类自动加载(autoload)
 *  2. 资源注册器(get,set)
 *  3. 类智能加载(call)(资源,短标签,原生态,短标签,映射,缓存)
 *  4. 类缓存控制
 *  5. 全局配置规划等
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
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   $Id$
 * @since     2010-04-26 10:39:18
 * @todo      其他缓存方式
 */

class Qwin
{
    const VERSION = '0.6.9';

    /**
     * 全局配置数组
     * @var array
     */
    protected static $_config = array();

    /**
     *
     * @var array               配置选项
     *
     *      -- cachePath        缓存文件的路径,缓存文件内容为数组,键名是类名,值是类所在文件
     *
     *      -- autoloadPath     是否自动将发现的类加入到缓存文件中
     */
    protected static $_option = array(
        'cacheFile'     => null,
        'lifetime'      => 86400,
        'autoloadPath'  => array(),
    );

    /**
     * 类名映射
     * @var array
     * @example self::$_map = array(
     *              '-url'     => 'Qwin_Url',
     *              '-request' => 'Qwin_Request',
     *              ...
     *          );
     */
    protected static $_map = array();

    /**
     * 短标签数组,通过短标签表示类名的前缀
     * @var array
     */
    protected static $_shortTag = array(
        '-' => 'Qwin_',
    );

    /**
     * 存储注册资源的数组
     * @var array
     */
    protected static $_data = array();

    /**
     * 类名和路径的缓存数组
     * @var array
     * @example self::$_classCache = array(
     *              'class_name_1' => 'class_path_1',
     *              'class_name_2' => 'class_path_2',
     *              ...
     *          );
     */
    protected static $_classCache = array();

    /**
     * 通过自动加载获取的类的缓存数组,对应关系同$_classCache
     *
     * @var array
     */
    protected static $_classAppendCache = array();

    /**
     * 自动加载的根路径数组
     *
     * @var array
     */
    protected static $_autoloadPath = array();

    /**
     * 设置配置选项
     *
     * @param array $option 配置选项,参见self::$_option
     */
    public static function setOption(array $option)
    {
        self::$_option = array_merge(self::$_option, $option);
        self::setCacheFile(self::$_option['cacheFile']);
        self::setAutoload(self::$_option['autoloadPath']);
    }

    /**
     * 获取一项资源
     *
     * @param string $name
     * @return mixed 资源
     */
    public static function get($name)
    {
        $name = strtolower($name);
        return isset(self::$_data[$name]) ? self::$_data[$name] : null;
    }

    /**
     * 设置一项资源
     *
     * @param string $name
     * @param mixed $value
     */
    public static function set($name, $value = null)
    {
        self::$_data[strtolower($name)] = $value;
    }

    /**
     * 以类名的形式加载/获取一项资源
     *
     * @param string $name 完整或短标签形式的类名
     * @param undefined|string|array 类初始化时的参数,将转换为数组 参数,以数组的形式出现,null表示没有参数,
     * @return null|object 类对象或空
     * @example Qwin::run('-url'); return new Qwin_Url();
     */
    public static function call($name, $param = null)
    {
        // 键名只接受字符串
        $name = (string)$name;
        /* @var $lower string 小写名称 */
        $lower = strtolower($name);

        // (一级)存在该资源,直接返回
        if (isset(self::$_data[$lower])) {
            return self::$_data[$lower];
        }

        // (二级)类可能是短标签形式,对短标签进行转换
        if (isset($name[0]) && isset(self::$_shortTag[$name[0]])) {
            /* @var $name2 string 转换后的名称 */
            $name2 = self::$_shortTag[$name[0]] . substr($name, 1);
            /* @var $name2 string 转换后的小写名称 */
            $lower2 = strtolower($name2);
            if (isset(self::$_data[$lower2])) {
                self::$_data[$lower] = self::$_data[$lower2];
                return self::$_data[$lower2];
            }
            if ($result = self::_callClass($name2, $param)) {
                self::$_data[$lower2] = $result;
                self::$_data[$lower] = $result;
                return $result;
            }
        }

        // (三级)通过直接加载
        if ($result = self::_callClass($name, $param)) {
            self::$_data[$lower] = $result;
            return $result;
        }

        // (四级)转换类名映射,如 url 映射为 Qwin_Url
        if (isset(self::$_map[$name])) {
            $name2 = self::$_map[$name];
            $lower2 = strtolower($name2);
            if (isset(self::$_data[$lower2])) {
                self::$_data[$lower] = self::$_data[$lower2];
                return self::$_data[$lower2];
            }
            if ($result = self::_callClass($name2, $param)) {
                self::$_data[$lower2] = $result;
                self::$_data[$lower] = $result;
                return $result;
            }
        }

        // 没有找到
        return false;
    }

    /**
     * 尝试初始化一个类,初始化之前,会将类名进行转换,符合类自动搜索需求
     *
     * @param string $name 类名
     * @param null|array $param 参数,以数组形式出现
     * @return object|false 实例化对象,或失败
     */
    protected static function _callClass($name, $param = null)
    {
        // 转换为标准格式的类名
        $name = preg_split('/([^A-Za-z0-9])/', $name);
        $name = implode('_', array_map('ucfirst', $name));
        
        if (class_exists($name)) {
            return self::_instanceClass($name, $param);
        }
        return false;
    }

    /**
     * 初始化一个类
     *
     * @param string $name 类名
     * @param null|array $param 参数
     * @return object 实例化的对象
     */
    protected static function _instanceClass($name, $param = null)
    {
        // 没有提供参数的情况下,尝试在配置中查找该类的参数,如果还是没有则直接初始化
        if (null === $param) {
            if (!$config = self::config($name)) {
                return new $name;
            }
            $param = $config;
        }

        // 有参数的情况下使用类反射进行初始化
        $param = is_array($param) ? $param : array($param);
        $reflection = new ReflectionClass($name);
        return $reflection->newInstanceArgs($param);
    }
    
    /**
     * 设置短标签
     *
     * @param string $name 名称,只能为一个字符,常用的为符号,如-,#,@ ...
     * @param string $value 值,如 Qwin_, Zend_ ...
     */
    public static function setShortTag($name, $value)
    {
        if (!is_string($name) || 1 != strlen($name)) {
            throw new Qwin_Exception('The short tag name should be a sting and the length is 1.');
        }
        if (!is_string($value)) {
            throw new Qwin_Exception('The short tag value should be a sting.');
        }
        self::$_shortTag[$name] = $value;
    }

    /**
     * 设置资源名称映射
     *
     * @param string $name 映射的原名称,一般为缩写形式
     * @param string $realName 映射的结果名称,一般为完整类名
     */
    public static function setMap($name, $realName)
    {
        if (!is_string($name)) {
            throw new Qwin_Exception('The map name should be a sting.');
        }
        if (!is_string($realName)) {
            throw new Qwin_Exception('The map real name should be a sting.');
        }
        self::$_map[$name] = strtolower($realName);
    }

    /**
     * 加载类缓存文件
     *
     * @param string $file 文件路径
     * @todo 怎么保证安全 ob_start() ?
     * @todo 如何保证缓存文件的完整性,不受外界影响
     * @todo 针对不同请求的不同缓存文件
     */
    public static function setCacheFile($file)
    {
        if (!is_file($file)) {
            require_once 'Qwin/Exception.php';
            throw new Qwin_Exception('The file "' . $file . '" is not exists.', '123');
        }
        self::$_option['cacheFile'] = $file;
        self::$_classCache = (array)require $file;

        register_shutdown_function(array('Qwin', 'updateCacheFile'));
    }

    /**
     * 更新类缓存到文件中
     *
     * @return boolen
     * @see Qwin_Util_File ::appendArray
     * @todo 对缓存文件的末端进行检测
     */
    public static function updateCacheFile()
    {
        // 重建缓存文件 todo 优化
        if (self::$_option['lifetime'] < $_SERVER['REQUEST_TIME'] - filemtime(self::$_option['cacheFile'])) {
            file_put_contents(self::$_option['cacheFile'], '<?php' . PHP_EOL . 'return array (' . PHP_EOL . ');');
        }

        if (!empty(self::$_classAppendCache)) {
            // 构建数组
            $code = substr(var_export(self::$_classAppendCache, true), 7) . ';';

            // 打开文件,并移动指针到倒数第三位倒数几位分别是 "换行符);"
            $fp = fopen(self::$_option['cacheFile'], 'r+');
            fseek($fp, -3, SEEK_END);
            fwrite($fp, $code);
            fclose($fp);
            return true;
        }
        return false;
    }

    /**
     * 设置自动加载的子路径
     *
     * @param array|string $pathList 自动加载的初始路径
     */
    public static function setAutoload($pathList = null)
    {
        // 设置自动加载的路径
        if (!is_array($pathList)) {
            $pathList[] = $pathList;
        }
        array_unshift($pathList, dirname(__FILE__));
        foreach ($pathList as $path) {
            self::$_autoloadPath[] = realpath($path) . DIRECTORY_SEPARATOR;
        }
        self::$_autoloadPath = array_unique(self::$_autoloadPath);

        // 将类库加入加载路径中
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
        spl_autoload_register(array('self', 'autoload'));
    }

    /**
     * 自动加载类的方法,适用各类按标注方法命名的类库
     * 并将加载到的类的文件路径加入缓存数组中
     *
     * @param string $className
     * @return bool 是否加载成功
     */
    public static function autoload($class)
    {
        // (五级)通过缓存文件的类数组加载
        $lower = strtolower($class);
        if (isset(self::$_classCache[$lower])) {
            require_once self::$_classCache[$lower];
            return true;
        }
        // 通过解析类名,获取文件路径加载
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR));
        foreach (self::$_autoloadPath as $path) {
            $path = $path . $class . '.php';
            if (file_exists($path)) {
                // 加入缓存数组
                self::$_classAppendCache[$lower] = $path;
                require_once $path;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取/设置配置
     *
     * @param mixed $name
     * @param mixed $param 配置内容
     * @return mixed
     * @example Qwin::config();                 // 获取所有配置
     *          Qwin::config('className');      // 获取此项的配置,建议为类名
     *          Qwin::config('array');          // 设定该数组为全局配置
     *          Qwin::config('name', 'param');  // 设定项为name的配置为param
     */
    public static function config($name = null)
    {
        // 获取所有配置
        if (null === $name ) {
            return self::$_config;
        }

        // 获取/设置某一项配置
        if (is_scalar($name)) {
            if (2 == func_num_args()) {
                return self::$_config[$name] = func_get_arg(1);
            }
            return isset(self::$_config[$name]) ? self::$_config[$name] : null;
        }

        // 设置全局配置
        if (is_array($name)) {
            return self::$_config = $name;
        }

        // 不匹配任何操作
        return null;
    }

    /**
     * 获取一个微件对象(加速方法)
     *
     * @param string $name 微件名称
     * @return Qwin_Widget_Abstract|Exception 微件对象或未找到微件的异常
     * @uses Qwin_Widget::get
     * @see Qwin_Widget
     */
    public static function widget($name)
    {
        return self::call('Qwin_Widget')->get($name);
    }

    /**
     * 设置一个钩子(加速方法)
     *
     * @param string $name 钩子名称
     * @param array $param 钩子参数
     * @uses Qwin_Hook::call
     * @see Qwin_Hook
     */
    public static function hook($name, array $param = null)
    {
        return self::call('Qwin_Hook')->call($name);
    }
}
