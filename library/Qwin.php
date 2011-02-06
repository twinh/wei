<?php
/**
 * 核心类,为一注册器,统一管理全部资源(类对象,数组配置等等)
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
     * 获取一项资源
     *
     * @param string $name
     * @return mixed 资源
     */
    public static function get($name)
    {
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
     * @param undefined|string|array 类初始化时的参数,将转换为数组
     * @return null|object 类对象或空
     */
    public static function call($name)
    {
        // 键名只接受字符串
        if (!is_string($name) || empty($name)) {
            return null;
        }
        
        // 因为 php 类名不区分大小写
        $name = strtolower($name);

        // 转换类名映射,如 -url 被映射为 Qwin_Url
        if (isset(self::$_map[$name])) {
            $name = self::$_map[$name];
        }

        // 已经实例化过
        if (isset(self::$_data[$name])) {
            return self::$_data[$name];
        }

        // 类存在,或者通过自动加载取得
        if (class_exists($name)) {
            self::$_data[$name] = self::_instanceClass(array(0 => $name) + func_get_args());
            return self::$_data[$name];
        }

        // 类可能是短标签形式,对短标签进行转换
        if (isset($name[0]) && isset(self::$_shortTag[$name[0]])) {
            $realName = self::$_shortTag[$name[0]] . substr($name, 1);
            $result = call_user_func_array(array('self', 'call'), array(0 => $realName) + func_get_args());
            // 如果存在此类,将短标签加入到映射中
            if (null != $result) {
                self::$_map[$name] = strtolower($realName);
            }
            return $result;
        }

        // 加载文件,实例化
        if (!empty($_classCache) && isset(self::$_classCache[$name])) {
            require_once self::$_classCache[$name];
            self::$_data[$name] = self::_instanceClass(array(0 => $name) + func_get_args());
            return self::$_data[$name];
        }

        // 没有找到
        return null;
    }

    /**
     * 初始化一个类
     *
     * @param array $arg 数组,0键为类名,1键为由参数组成的数组
     * @return object 实例化的对象
     */
    protected static function _instanceClass(array $arg)
    {
        // 没有提供参数的情况下,尝试在配置中查找该类的参数,如果还是没有则直接初始化
        if (!isset($arg[1])) {
            if (!$config = self::config($arg[0])) {
                return new $arg[0];
            }
            $arg[1] = $config;
        }
        // 有参数的情况下使用类反射进行初始化
        $arg[1] = is_array($arg[1]) ? $arg[1] : array($arg[1]);
        $reflection = new ReflectionClass($arg[0]);
        return $reflection->newInstanceArgs($arg[1]);
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

    public static function widget($name)
    {
        
    }

    protected $_hook;


    /**
     * 设置一个钩子
     *
     * @param string $name 钩子名称
     * @param array $param 钩子参数
     * @uses Qwin_Hook
     * @see Qwin_Hook
     * @todo
     */
    public static function hook($name, array $param = null)
    {
        if (!isset(self::$_hook)) {
            $config = Qwin::call('-config');
            self::$_hook = self::call('Qwin_Hook');
        }
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

        // 获取某一对象的初始配置
        /*if (is_object($name)) {
            $name = get_class($name);
        }*/

        // 获取/设置某一项配置
        if (is_scalar($name)) {
            if (2 != func_num_args()) {
                return isset(self::$_config[$name]) ? self::$_config[$name] : null;
            } else {
                return self::$_config[$name] = func_get_arg(1);
            }
        }

        // 设置全局配置
        if (is_array($name)) {
            return self::$_config = $name;
        }

        // 不匹配任何操作
        return null;
    }
}
