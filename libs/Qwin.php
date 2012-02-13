<?php
/**
 * Qwin Framework
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * @see Qwin_Widget
 */
require_once 'Qwin/Widget.php';

/**
 * Qwin
 *
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-04-26 10:39:18
 * @todo        add ini_set support
 */
class Qwin extends Qwin_Widget
{
    /**
     * 版本
     */
    const VERSION = '0.8.4';

    /**
     * 存储微件对象的数组
     * @var array
     */
    protected $_widgets = array();

    /**
     * 存储类对象的数组
     * @var array
     */
    protected $_objects = array();

    /**
     * 存储值不为字符串或整数的微件键名
     * @var array
     */
    protected $_varKeys = array();

    /**
     * 存储值不为字符串或整数的微件值
     * @var array
     */
    protected $_varValues = array();

    /**
     * 存储全局配置的数组
     * @var array
     */
    protected $_config = array();

    /**
     * 原始全局变量$q的备份
     * @var mixed
     */
    public $globalQ;

    /**
     * 当前实例化对象
     * @var Qwin
     */
    protected static $_instance;

    /**
     * 选项
     *
     * @var array
     *       fnQ            bool        是否定义全局函数"q"
     *
     *       autoload       bool        是否启用类自动加载
     *
     *       autoloadPaths  array       类自动加载的目录
     */
    public $options = array(
        'fnQ'           => true,
        'autoload'      => true,
        'autoloadPaths' => array(),
    );

    /**
     * Widgets map
     *
     * key  =>  value
     *  0   =>  callback name
     *  1   =>  source paramter position
     *  2   =>  max parameter numbers
     *  3   =>  0 => $invoker, 1 => $result
     *
     * @var array
     *
     */
    protected $_widgetsMap = array(
        'isArray' => array(
            'is_array', 0, 1, 1
        ),
        'isString' => array(
            'is_string', 0, 1, 1
        ),
        'isNull' => array(
            'is_null', 0, 1, 1
        ),
        'lower' => array(
            'strtolower', 0, 1,
        ),
        'upper' => array(
            'strtoupper', 0, 1,
        ),
        'upperFirst' => array(
            'ucfirst', 0, 1,
        ),
        'urlDecode' => array(
            'urldecode', 0, 1,
        ),
        'urlEnCode' => array(
            'urlencode', 0, 1,
        ),
        'trim' => array(
            'trim', 0,
        ),
        'ltrim' => array(
            'ltrim', 0,
        ),
        'rtrim' => array(
            'rtrim', 0,
        ),
        'join' => array(
            'implode', 1,
        ),
        'pad' => array(
            'str_pad',
        ),
        'repeat' => array(
            'str_repeat',
        ),
        'shuffle' => array(
            'str_shuffle',
        ),
        'md5' => array(
            'md5',
        ),
        'sha1' => array(
            'sha1',
        ),
    );

    /**
     * 初始化Qwin微件
     *
     * @return Qwin
     */
    public function __construct(array $config = array())
    {
        if (isset(self::$_instance)) {
            require_once 'Qwin/Exception.php';
            throw new Qwin_Exception('Class "Qwin" can only have one instance.');
        }

        $this->config($config);
        if (isset($config[__CLASS__])) {
            $this->options = $config[__CLASS__] + $this->options;
        }
        $options = &$this->options;

        // 定义全局函数Q
        if ($options['fnQ'] && !function_exists('q')) {
            function q($value = null) {
                return Qwin::getInstance()->variable($value);
            }
        }

        // 定义全局函数Qwin
        function qwin($value = null) {
            return Qwin::getInstance()->variable($value);
        }

        // 定义全局变量$q
        if (isset($GLOBALS['q'])) {
            $this->globalQ = &$GLOBALS['q'];
        }
        $GLOBALS['q'] = $this;

        // 将类库路径加入加载路径中的第二位
        $file = dirname(__FILE__);
        $includePath = get_include_path();
        $pos = strpos($includePath, PATH_SEPARATOR);
        if ($pos) {
            $includePath = substr_replace($includePath, $file . PATH_SEPARATOR, $pos + 1, 0);
        } else {
            $includePath .= PATH_SEPARATOR . $file;
        }
        set_include_path($includePath);

        // 设置自动加载
        if ($options['autoload']) {
            $paths = &$options['autoloadPaths'];
            !is_array($paths) && $paths = (array)$paths;
            foreach ($paths as &$path) {
                $path = realpath($path) . DIRECTORY_SEPARATOR;
            }
            $paths[] = $file . DIRECTORY_SEPARATOR;
            $paths = array_unique($paths);

            spl_autoload_register(array($this, 'autoload'));
        }

        $this->_widgets['qwin'] = $this;
        $this->_objects['Qwin'] = $this;
    }

    /**
     * 自动加载按标准格式命名的类
     *
     * @param string $class 类名
     * @return bool 是否加载成功
     * @todo 缓存加载过的类
     */
    public function autoload($class)
    {
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR));
        foreach ($this->options['autoloadPaths'] as $path) {
            $path = $path . $class . '.php';
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }
        return false;
    }

    /**
     * 调用一个微件
     *
     * @param string $name 微件名称
     * @return Qwin_Widget 微件实例化对象
     */
    public function widget($name)
    {
        $lower = strtolower($name);

        if (isset($this->_widgets[$lower])) {
            return $this->_widgets[$lower];
        }

        $class = 'Qwin_' . ucfirst($name);
        if (class_exists($class)) {
            return $this->_widgets[$lower] = $this->call($class);
        }

        $this->exception('Widget, property or method "%s" not found.', $name);
    }

    /**
     * Instance a class
     *
     * @param string $class Class name
     */
    public function instance($class)
    {

    }

    /**
     * 初始化一个类
     *
     * @param string $name 类名
     * @param null|array $param 类初始化时的参数,以数组的形式出现
     * @return false|object 失败或类对象
     * @todo reanem to instance ?
     */
    public function call($name, $param = null)
    {
        if (isset($this->_objects[$name])) {
            return $this->_objects[$name];
        }

        if (!class_exists($name)) {
            return false;
        }

        // 获取参数
        $param = null !== $param ? $param : $this->config($name);
        !is_array($param) && $param = (array)$param;

        // 标准单例模式
        if (method_exists($name, 'getInstance')) {
            return call_user_func_array(array($name, 'getInstance'), $param);
        }

        // 根据参数数目初始化类
        switch (count($param)) {
            case 0:
                $object = new $name;
                break;

            case 1:
                $object = new $name(current($param));
                break;

            case 2:
                $object = new $name(current($param), next($param));
                break;

            case 3:
                $object = new $name(current($param), next($param), next($param));
                break;

            default:
                if (method_exists($name, '__construct') || method_exists($name, $name)) {
                    $reflection = new ReflectionClass($name);
                    $object = $reflection->newInstanceArgs($param);
                } else {
                    $object = new $name;
                }
        }
        return $this->_objects[$name] = $object;
    }

    /**
     * 获取/设置配置
     *
     * @param mixed $name 配置的值,多级用'/'分开
     * @param mixed $param 配置内容
     * @return mixed
     * @example $this->config();                 // 获取所有配置
     *          $this->config('className');      // 获取此项的配置,建议为类名
     *          $this->config('array');          // 设定该数组为全局配置
     *          $this->config('name', 'param');  // 设定项为name的配置为param
     *          $this->config('key1/key2');      // 获取$config[key1][key2]的值
     */
    public function config($name = null)
    {
        // 获取所有配置
        if (null === $name ) {
            return $this->_config;
        }

        // 获取/设置某一项配置
        if (is_string($name) || is_int($name)) {
            $temp = &$this->_config;
            if (false !== strpos($name, '/')) {
                $array = explode('/', $name);
                $name = array_pop($array);
                foreach ($array as $value) {
                    if (!isset($temp[$value])) {
                        $temp[$value] = null;
                    }
                    $temp = &$temp[$value];
                }
            }

            if (2 == func_num_args()) {
                return $temp[$name] = func_get_arg(1);
            }
            return isset($temp[$name]) ? $temp[$name] : null;
        }

        // 设置全局配置
        if (is_array($name)) {
            return $this->_config = $name;
        }

        // 不匹配任何操作
        return null;
    }

    /**
     * 初始化一个变量微件
     *
     * @param mixed $mixed variable
     * @return Qwin_Widget
     */
    public function variable($mixed = null, $class = 'Qwin_Widget')
    {
        $this->_varKeys[] = $mixed;
        return $this->_varValues[] = new $class($mixed);
    }

    /**
     * Get instance
     *
     * @param mixed $config [optional] config file path or array
     * @param mixed $_ [optional]
     * @return Qwin
     */
    public static function getInstance($config = null)
    {
        if (!$config) {
            if (isset(self::$_instance)) {
                return self::$_instance;
            }
            return self::$_instance = new self();
        }

        // Merge all configs
        $args = func_get_args();
        $config = array();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $config = $arg + $config;
            } elseif (is_string($arg) && is_file($arg)) {
                $config = ((array)require $arg) + $config;
            } else {
                require_once 'Qwin/Exception.php';
                throw new Qwin_Exception('Config should be array or file.');
            }
        }

        if (isset(self::$_instance)) {
            self::$_instance->config($config);
            return self::$_instance;
        }
        return self::$_instance = new self($config);
    }

    /**
     * Get a widget obejct and call the "call" method
     *
     * @param Qwin_Widget $invoker
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function callWidget($invoker, $name, $args)
    {
        $lower = strtolower($name);
        if (isset($this->_widgetsMap[$name])) {
            $map = &$this->_widgetsMap[$name];

            if (isset($map[1])) {
                array_splice($args, $map[1], 0, array($invoker->source));
            } else {
                $args = array($invoker->source);
            }

            if (isset($map[2]) && $map[2] < count($args)) {
                $args = array_slice($args, 0, $map[2]);
            }

            $result = call_user_func_array($this->_widgetsMap[$name][0], $args);

            $invoker->source = $result;
            if (!isset($map[3]) || 0 === $map[3]) {
                return $invoker;
            } else {
                return $result;
            }
        }

        $widget = $this->widget($name);

        if (!method_exists($widget, 'call')) {
            require_once 'Qwin/Exception.php';
            throw new Qwin_Exception('Method "call" not found in widget "' . get_class($widget) . '"');
        }

        // set invoker for widget
        $widget->invoker = $invoker;

        // set source for widget
        $widget->source = $invoker->source;

        // call widget
        $result = call_user_func_array(array($widget, 'call'), $args);

        // set back source so that source can be passed in widgets
        $invoker->source = $widget->source;

        // return result rather than widget object
        return $result;
    }

    /**
     * convert $var to a widget
     *
     * @param mixed $var
     * @return Qwin_Widget
     */
    public function __invoke($var = null)
    {
        return $this->variable($var);
    }
}