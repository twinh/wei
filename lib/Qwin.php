<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

use Qwin\Widget;

/**
 * @see Qwin\Widget
 */
require_once 'Qwin/Widget.php';

/**
 * The root widget and widget manager 
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        autoload interaction with composer ?
 * @todo        \Qwin or Qwin\Qwin ? 
 */
class Qwin extends Widget
{
    /**
     * Version
     */
    const VERSION = '0.8.8';

    /**
     * 存储微件对象的数组
     *
     * @var array
     */
    protected $widgets = array();

    /**
     * Global configurations of all widgets
     *
     * @var array
     */
    protected $config = array();

    /**
     * The instance of Qwin
     *
     * @var \Qwin
     */
    protected static $instance;

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
     *       widgetMap     array       widget name => new class name
     */
    public $options = array(
        'inis'          => array(),
        'autoload'      => true,
        'autoloadDirs'  => array(),
        'widgetMap'     => array(),
        'widget'        => null,
        'invoker'       => null,
        'deps'          => array(),
        'initWidgets'   => array(),
        'funcMap'       => array(
            'isArray'       => 'is_array',
            'isBool'        => 'is_bool',
            'isInt'         => 'is_int', 
            'isNull'        => 'is_null',
            'isNumeric'     => 'is_numeric',
            'isScalar'      => 'is_scalar',
            'isString'      => 'is_string',
        ),
    );

    /**
     * Instance qwin widget
     *
     * @return \Qwin
     */
    public function __construct(array $config = array())
    {
        $name = 'qwin';
        
        $this->config($config);
        if (isset($config[$name])) {
            $this->options = $config[$name] + $this->options;
        }
        $options = &$this->options;
        $options['widget'] = $this;
        
        $this->widgets['qwin'] = $this;
        $this->widgets['widget'] = $this;
        
        parent::__construct($options);
        
        // call
        foreach ($options['initWidgets'] as $widgetName) {
            $this->getWidget($widgetName, null, $this);
        }
    }

    /**
     * 自动加载按标准格式命名的类
     *
     * @param string $class the name of the class
     * @return bool
     * @todo class prefix
     */
    public function autoload($class)
    {
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
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
     * what to do ?
     */
    public function __invoke()
    {
        
    }
    
    /**
     * Get or set widget's configuration
     *
     * @param mixed $name the name of configuration
     * @param mixed $param the value of configuration
     * @return mixed
     * @example $this->config();                 // get all configurations
     *          $this->config('widgetName');     // 获取此项的配置,建议为类名
     *          $this->config('array');          // 设定该数组为全局配置
     *          $this->config('name', 'param');  // 设定项为name的配置为param
     *          $this->config('key1/key2');      // 获取$config[key1][key2]的值
     */
    public function config($name = null)
    {
        // get all configurations
        if (null === $name ) {
            return $this->config;
        }

        // get or set one configuration
        if (is_string($name) || is_int($name)) {
            $temp = &$this->config;
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

        // set global configurations
        if (is_array($name)) {
            return $this->config = $name;
        }

        // not match any actions
        return null;
    }

    /**
     * Get Qwin class instance
     *
     * @param mixed $config [optional] config file path or array
     * @param mixed $ [optional] TODO remove extra args ?
     * @return \Qwin
     * @todo rename to create ?
     */
    public static function getInstance($config = array())
    {
        // most of time, it's called after instanced and without arguments
        if (!$config && isset(static::$instance)) {
            return static::$instance;
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
                    throw new \InvalidArgumentException('Config should be array or file.');
                }
            }
        }

        if (!isset(static::$instance)) {
            static::$instance = new static($config);
        } else {
            static::$instance->config($config);
        }

        return static::$instance;
    }

    /**
     * Get a widget object and call its "__invoke" method
     * 
     * @param string $name the name of widget
     * @param array $args arguments for "__invoke" method
     * @param string $config
     * @param \Qwin\Widget $invoker invoker
     * @return mixed
     * @throws \Qwin\Exception when method "__invoke" not found
     */
    public function invokeWidget($name, array $args, $config = null, Widget $invoker = null)
    {
        // check if function widget
        if (isset($this->options['funcMap'][$name])) {
            return call_user_func_array($this->options['funcMap'][$name], $args);
        }

        $widget = $this->getWidget($name, $config, $invoker);

        if (!method_exists($widget, '__invoke')) {
            return $this->exception('Method "__invoke" not found in widget "' . get_class($widget) . '"');
        }

        // set invoker for widget
        if (isset($widget->options['invoker'])) {
            $widget->options['invoker'] = $invoker;
        }

        return call_user_func_array(array($widget, '__invoke'), $args);
    }
    
    /**
     * Get a widget instance
     *
     * @param string $name the name of the widget, without class prefix "Qwin_"
     * @return Widget the widget object
     */
    public function getWidget($name, $config = null, Widget $invoker = null)
    {
        if ($config) {
            $full = $name . '.' . $config;
        } elseif ($invoker && $deps = $invoker->option('deps')) {
            if (isset($deps[$name])) {
                $full = $deps[$name];
            } else {
                $full = $name;
            }
        } else {
            $full = $name;
        }

        $lower = strtolower($full);

        // todo shared or not ?
        if (isset($this->widgets[$lower])) {
            return $this->widgets[$lower];
        }

        if (isset($this->options['widgetMap'][$name])) {
            $class = $this->options['widgetMap'][$name];
        } else {
            $class = 'Qwin\\' . ucfirst($name);
        }

        if (class_exists($class)) {
            $options = $this->config($full);
            
            if (null === $options && $this->config($name)) {
                return $this->exception('Config name "' . $full . '" not found');
            }
            
            $options = array(
                'widget' => $this,
                'invoker' => $invoker,
            ) + (array)$options;
            
            return $this->widgets[$lower] = new $class($options);
        }

        $traces = debug_backtrace();

        // called by class ?
        if (isset($traces[1]) && '__get' == $traces[1]['function'] && $name == $traces[1]['args'][0]) {
            throw new Qwin\Exception(sprintf('Widget "%s" (class "%s") not found call in file "%s" at line %s', $traces[1]['args'][0], $class, $traces[1]['file'], $traces[1]['line']));
        } elseif (isset($traces[3]) && $name == $traces[3]['function']) {
            // call_user_func
            // or call to undefiend method...
            $file = isset($traces[3]['file']) ? $traces[3]['file'] : $traces[4]['file'];
            $line = isset($traces[3]['line']) ? $traces[3]['line'] : $traces[4]['line'];
            throw new Qwin\Exception(sprintf('Widget "%s" (class "%s") not found, call in file "%s" at line %s', $traces[3]['function'], $class, $file, $line));
        } else {
            // would this happen ?
            //Call to undefined method class::method
            //Undefined property: class::$property
            throw new Qwin\Exception(sprintf('Property or method "%s" not defined', $name));
        }
    }
    
    /**
     * Remove widget
     * 
     * @param string $name the name of widget
     * @return boolean
     */
    public function removeWidget($name)
    {
        $lower = strtolower($name);
        
        if (isset($this->widgets[$lower])) {
            unset($this->widgets[$lower]);
            return true;
        }
        
        return $this->exception('Widget "' . $name . '" not found');
    }
    
    public function hasWidget($name)
    {
        $widgetMap = $this->options['widgetMap'];
        
        if (isset($widgetMap[$name])) {
            $class = $widgetMap[$name];
        } else {
            $class = 'Qwin\\' . ucfirst($name);
        }
        
        return class_exists($class);
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
     * @return \Qwin
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
     * @return \Qwin
     */
    public function setInisOption($inis)
    {
        foreach ($inis as $key => $value) {
            ini_set($key, $value);
        }
        return $this;
    }
}