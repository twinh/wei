<?php
/**
 * Qwin框架核心类,使用注册模式,统一管理全部资源(类对象,数组配置等等),包含的特性如下
 *  1. 类自动加载(autoload)
 *  2. 资源注册器(get,set)
 *  3. 类智能加载(call)(资源,短标签,原生态,短标签,映射,缓存)
 *  4. 类缓存控制
 *  5. 全局配置规划等
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
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   $Id$
 * @since     2010-04-26 10:39:18
 * @todo      其他缓存方式
 */

/**
 * 定义常量QWIN
 */
define('QWIN', true);

class Qwin
{
    const VERSION = '0.7.9';

    /**
     * 全局配置数组
     * @var array
     */
    public static $_config = array();

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
     * 自动加载的根路径数组
     *
     * @var array
     */
    protected static $_autoloadPaths = array();

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
            /* @var $lower2 string 转换后的小写名称 */
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
        return null;
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
     * @todo 是否应该实现类替换 Qwin_Request -> Common_Request
     */
    protected static function _instanceClass($name, $param = null)
    {
        $param = null !== $param ? $param : self::config($name);

        // 标准单例模式
        if (method_exists($name, 'getInstance')) {
            return call_user_func_array(array($name, 'getInstance'), $param);
        }

        // 没有提供参数的情况下,直接初始化
        if (empty($param)) {
            return new $name;
        } else {
            // TODO 参数少的情况下,不使用类反射
            // 有参数的情况下使用类反射进行初始化
            $reflection = new ReflectionClass($name);
            return $reflection->newInstanceArgs((array)$param);
        }
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
        if (is_object($realName)) {
            $realName = get_class($realName);
        }
        if (!is_string($realName)) {
            throw new Qwin_Exception('The map real name should be a sting.');
        }
        self::$_map[$name] = strtolower($realName);
    }


    /**
     * 设置自动加载的子路径
     *
     * @param array|string $paths 自动加载的初始路径
     * @todo 多次执行可能混乱
     */
    public static function setAutoload($paths = null)
    {
        // 设置自动加载的路径
        $file = dirname(__FILE__);
        !is_array($paths) && $paths = (array)$paths;
        foreach ($paths as &$path) {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
        }
        array_push($paths, $file . DIRECTORY_SEPARATOR);
        self::$_autoloadPaths = array_unique($paths);
        
        /**
         * 将类库加入加载路径中的第二位
         * 默认情况下,php的加载路径为"当前目录;PEAR目录",而很多时候,PEAR目录是不可控的,例如,存在陈旧的类文件却无法
         * 更新,所以自定义的类库应该置于PEAR目录之前
         * @todo 
         */
        $includePath = get_include_path();
        $pos = strpos($includePath, PATH_SEPARATOR);
        if ($pos) {
            $includePath = substr_replace($includePath, $file . PATH_SEPARATOR, $pos + 1, 0);
        } else {
            $includePath .= PATH_SEPARATOR . $file;
        }
        set_include_path($includePath);
        spl_autoload_register(array('self', 'autoload'));
    }
    
    public static function getAutoloadPaths()
    {
        return self::$_autoloadPaths;
    }

    /**
     * 自动加载类的方法,适用各类按标注方法命名的类库
     * 并将加载到的类的文件路径加入缓存数组中
     *
     * @param string $className
     * @return bool 是否加载成功
     * @todo 重新开启缓存功能
     */
    public static function autoload($class)
    {
        // 通过解析类名,获取文件路径加载
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR));
        foreach (self::$_autoloadPaths as $path) {
            $path = $path . $class . '.php';
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取/设置配置
     *
     * @param mixed $name 配置的值,多级用'/'分开
     * @param mixed $param 配置内容
     * @return mixed
     * @example Qwin::config();                 // 获取所有配置
     *          Qwin::config('className');      // 获取此项的配置,建议为类名
     *          Qwin::config('array');          // 设定该数组为全局配置
     *          Qwin::config('name', 'param');  // 设定项为name的配置为param
     *          Qwin::config('key1/key2');      // 获取$config[key1][key2]的值
     */
    public static function config($name = null)
    {
        // 获取所有配置
        if (null === $name ) {
            return self::$_config;
        }

        // 获取/设置某一项配置
        if (is_scalar($name)) {
            $temp = &self::$_config;
            if (false !== strpos($name, '/')) {
                $array = explode('/', $name);
                $name = array_pop($array);
                foreach ($array as $value) {
                    if (isset($temp[$value])) {
                        $temp = &$temp[$value];
                    } else {
                        return null;
                    }
                }
            }

            if (2 == func_num_args()) {
                return $temp[$name] = func_get_arg(1);
            }
            return isset($temp[$name]) ? $temp[$name] : null;
        }

        // 设置全局配置
        if (is_array($name)) {
            return self::$_config = new ArrayObject($name, ArrayObject::ARRAY_AS_PROPS);
        }

        // 不匹配任何操作
        return null;
    }

    /**
     * 设置一个钩子
     *
     * @param string $name 钩子名称
     * @param array $param 钩子参数
     */
    public static function hook($name, array $options = array())
    {
        return self::call('-hook')->call($name, $options);
    }

    /**
     * 调用一个微件
     *
     * @param string $name 微件名称
     * @return Qwin_Widget_Abstract 微件实例化对象
     */
    public static function widget($name)
    {
        return self::call('-widget')->call($name);
    }

    /**
     * 启动
     *
     * @param string|array $config 配置文件的路径或配置数组
     * @param string|array $config2 附加的配置数据,例如有不同的入口文件,指向不同的模块操作
     *                              通过定义附加配置,即可方便实现
     * @return mixed 结果
     * @todo 支持不定长长度的参数
     */
    public static function startup($config, $config2 = null)
    {
        // 合并配置
        if (!is_array($config)) {
            if (file_exists($config)) {
                $config = require $config;
            } else {
                require_once 'Qwin/Exception.php';
                throw new Qwin_Exception('File "' . $config . '" not found.');
            }
        }
        // 设定全局配置
        $config = self::config((array)$config2 + (array)$config);

        // 设置自动加载
        self::setAutoload($config['Qwin']['autoloadPaths']);

        // 启动应用
        return self::widget('app')->render($config);
    }
}
