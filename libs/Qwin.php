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
 * @package     Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yaho 10:39:18o.cn>
 * @since       2010-04-26
 */
class Qwin extends Qwin_Widget
{
    /**
     * Version
     */
    const VERSION = '0.8.7';

    /**
     * 存储微件对象的数组
     *
     * @var array
     */
    protected $_widgets = array();

    /**
     * 存储类对象的数组
     *
     * @var array
     */
    protected $_objects = array();

    /**
     * Global configurations of all widgets
     *
     * @var array
     */
    protected $_config = array();

    /**
     * Backup of gloabal variable $q
     *
     * @var mixed
     */
    public $globalQ;

    /**
     * The instance of Qwin
     *
     * @var Qwin
     */
    protected static $_instance;

    /**
     * Options
     *
     * @var array
     *
     *       inis           array       ini options
     *
     *       autoload       bool        whether enable autoload or not
     *
     *       autoloadDirs   array       the direcroties of classes
     *
     *       classMaps      array       class maps
     */
    public $options = array(
        'inis'          => array(),
        'autoload'      => true,
        'autoloadDirs'  => array(),
        'classMaps'     => array(),
    );

    /**
     * Internal widgets configurations
     *
     * @var array
     *
     */
    protected $_widgetsMap = array(
        'isArray' => 'is_array',
        'isString' => 'is_string',
        'isNull' => 'is_null',
    );

    /**
     * Instance qwin widget
     *
     * @return Qwin
     */
    public function __construct(array $config = array())
    {
        $this->config($config);
        if (isset($config[__CLASS__])) {
            $this->options = $config[__CLASS__] + $this->options;
        }
        $options = &$this->options;

        // define global variable $q
        if (isset($GLOBALS['q'])) {
            $this->globalQ = &$GLOBALS['q'];
        }
        $GLOBALS['q'] = $this;

        // set library directory as the second include path
        $file = dirname(__FILE__);
        $includePath = get_include_path();

        // check if it has two or more include paths
        $pos = strpos($includePath, PATH_SEPARATOR);

        // insert into the second potion or append it to the end of whole include path
        $includePath = $pos
            ? substr_replace($includePath, $file . PATH_SEPARATOR, $pos + 1, 0)
            : $includePath . PATH_SEPARATOR . $file;

        set_include_path($includePath);

        $this->option($options);

        $this->_widgets['qwin'] = $this;
        $this->_objects['Qwin'] = $this;
    }

    /**
     * 自动加载按标准格式命名的类
     *
     * @param string $class the name of the class
     * @return bool
     */
    public function autoload($class)
    {
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR));
        foreach ($this->options['autoloadDirs'] as $dir) {
            $file = $dir . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        return false;
    }

    /**
     * 调用一个微件
     *
     * @param string $name the name of the widget, without class prefix "Qwin_"
     * @return Qwin_Widget 微件实例化对象
     */
    public function widget($name)
    {
        $lower = strtolower($name);

        if (isset($this->_widgets[$lower])) {
            return $this->_widgets[$lower];
        }

        $class = 'Qwin_' . ucfirst($name);

        if (isset($this->options['classMaps'][$class])) {
            $class = $this->options['classMaps'][$class];
        }

        if (class_exists($class)) {
            return $this->_widgets[$lower] = $this->__invoke($class);
        }

        $this->exception('Widget, property or method "%s" not found.', $name);
    }

    /**
     * 初始化一个类
     *
     * @param string $name 类名
     * @param null|array $param 类初始化时的参数,以数组的形式出现
     * @return false|object 失败或类对象
     * @todo reanem to instance ?
     */
    public function __invoke($name, $param = null)
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
     * Get Qwin class instance
     *
     * @param mixed $config [optional] config file path or array
     * @param mixed $_ [optional]
     * @return Qwin
     */
    public static function getInstance($config = array())
    {
        // most of time, it's called after instanced and without arguments
        if (!$config && isset(self::$_instance)) {
            return self::$_instance;
        }

        // merge all configurations
        if ($config) {
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
        }

        if (!isset(self::$_instance)) {
            self::$_instance = new self($config);
        } else {
            self::$_instance->config($config);
        }

        return self::$_instance;
    }

    /**
     * Get a widget object and call its "__invoke" method
     *
     * @param Qwin_Widget $invoker the invker widget object
     * @param string $name the name of the widget
     * @param array $args the arguments for "call" method
     * @return mixed
     */
    public function invokeWidget(Qwin_Widget $invoker, $name, $args)
    {
        // check if internal widget
        if (isset($this->_widgetsMap[$name])) {
            return call_user_func_array($this->_widgetsMap[$name], $args);
        }

        $widget = $this->widget($name);

        if (!method_exists($widget, '__invoke')) {
            require_once 'Qwin/Exception.php';
            throw new Qwin_Exception('Method "__invoke" not found in widget "' . get_class($widget) . '"');
        }

        // set invoker and soure value for widget
        $widget->__invoker = $invoker;

        return call_user_func_array(array($widget, '__invoke'), $args);
    }

    /**
     * Whether enable autoload method
     *
     * @param bool $enable
     */
    public function setAutoloadOption($enable)
    {
        if ($enable) {
            spl_autoload_register(array($this, 'autoload'));
        } else {
            spl_autoload_unregister(array($this, 'autoload'));
        }
        $this->options['autoload'] = (bool)$enable;
        return $this;
    }

    /**
     * Set autoload directories for autoload method
     *
     * @param string|array $dirs
     * @return Qwin
     */
    public function setAutoloadDirsOption($dirs)
    {
        !is_array($dirs) && $dirs = (array)$dirs;
        foreach ($dirs as &$dir) {
            $dir = realpath($dir) . DIRECTORY_SEPARATOR;
        }
        // the autoload directories will always contain the directory of the class file
        $dirs[] = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $dirs = array_unique($dirs);

        $this->options['autoloadDirs'] = $dirs;
        return $this;
    }

    /**
     * Set the ini options
     *
     * @param array $inis
     * @return Qwin
     */
    public function setInisOption($inis)
    {
        foreach ($inis as $key => $value) {
            ini_set($key, $value);
        }
        return $this;
    }
}