<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * @see Qwin\Widget
 */
require_once 'Widget.php';

/**
 * The root widget and widget manager
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        autoload interaction with composer ?
 * @todo        set first or get first ?
 * @todo        not shared widgets ?
 */
class WidgetManager extends Widget
{
    /**
     * Version
     */
    const VERSION = '0.8.9';
    
    /**
     * The instance of widget manager
     *
     * @var \Qwin\WidgetManager
     */
    protected static $instance;
    
    /**
     * The array contains the instanced widget objects
     *
     * @var array
     */
    protected $widgets = array();

    /**
     * The global configurations for all widgets
     *
     * @var array
     */
    protected $config = array();

    /**
     * The php configuration options that will be set when widget manager constructing
     * 
     * @var array 
     * @see http://www.php.net/manual/en/ini.php
     * @see http://www.php.net/manual/en/function.ini-set.php
     */
    protected $inis = array();
    
    /**
     * The direcroties for autoload
     * 
     * @var array
     */
    protected $autoloadDirs = array();
    
    /**
     * Whether enable autoload or not
     * 
     * @var bool
     */
    protected $autoload = true;
    
    /**
     * The widget name to class name map
     * 
     * @var array
     */
    protected $widgetMap = array();
    
    /**
     * The widgets that will be instanced after widget manager constructed
     * 
     * @var array
     */
    protected $initWidgets = array();
    
    /**
     * Instance widget manager
     * 
     * @param array $config
     * @return \Qwin\WidgetManager
     */
    public function __construct(array $config = array())
    {
        // set configurations for all widget
        $this->config = $config;
        
        $this->widgetManager = $this;

        // TODO full properties
        // set options for current widget
        if (isset($config['widgetManager'])) {
            $this->option($config['widgetManager']);
        }
        
        // instance initial widgets
        foreach ($this->initWidgets as $widgetName) {
            $this->getWidget($widgetName, null, $this);
        }
    }

    /**
     * 自动加载按标准格式命名的类
     *
     * @param  string $class the name of the class
     * @return bool
     * @todo class prefix
     */
    public function autoload($class)
    {
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        foreach ($this->autoloadDirs as $dir) {
            $file = $dir . $class . '.php';
            if (file_exists($file)) {
                require_once $file;

                return true;
            }
        }

        return false;
    }

    /**
     * Get a widget instance
     *
     * @param  string       $name The name of widget
     * @return \Qwin\Widget
     */
    public function __invoke($name)
    {
        return $this->getWidget($name, null, $this);
    }

    /**
     * Get or set widget's configuration
     *
     * @param  mixed $name  The name of configuration
     * @param  mixed $param The value of configuration
     * @return mixed
     * @example $this->config();                 // Get all configurations
     *          $this->config('widgetName');     // Get the configuration for 'widgetName'
     *          $this->config($array);           // Replace all configurations
     *          $this->config('name', 'param');  // Set one configuration
     *          $this->config('key1/key2');      // Get the value of $this->config['key1']['key2']
     */
    public function config($name = null)
    {
        // get all configurations
        if (null === $name) {
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
     * Get widget manager instance
     *
     * @param mixed $config [optional] The configurations file path or array
     * @param mixed $ [optional]
     * @return \Qwin\WidgetManager
     * @todo remove extra args ?
     */
    public static function getInstance($config = array())
    {
        // most of time, it's called after instanced and without any arguments
        if (!$config && isset(static::$instance)) {
            return static::$instance;
        }

        // merge all configurations
        if ($config) {
            $config = array();
            foreach (func_get_args() as $arg) {
                if (is_array($arg)) {
                    $config = $arg + $config;
                } elseif (is_string($arg) && is_file($arg)) {
                    $config = ((array) require $arg) + $config;
                } else {
                    throw new \InvalidArgumentException('Configuration should be array or file.');
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
     * Reset the internal static instance
     *
     * @return void
     */
    public static function resetInstance()
    {
        if (static::$instance) {
            static::$instance = null;
        }
    }

    /**
     * Get a widget object and call its "__invoke" method
     *
     * @param  string          $name   The name of widget
     * @param  array           $args   The arguments for "__invoke" method
     * @param  string          $config
     * @return mixed
     * @throws \Qwin\Exception When method "__invoke" not found
     */
    public function invokeWidget($name, array $args, $config = null, $deps = array())
    {
        $widget = $this->getWidget($name, $config, $deps);

        if (!method_exists($widget, '__invoke')) {
            return $this->exception('Method "__invoke" not found in widget "' . get_class($widget) . '"');
        }

        return call_user_func_array(array($widget, '__invoke'), $args);
    }

    /**
     * Get a widget instance
     *
     * @param  string $name The name of the widget, without class prefix "Qwin\"
     * @return \Qwin\Widget
     */
    public function getWidget($name, $config = null, $deps = array())
    {
        if ($config) {
            $full = $name . '.' . $config;
        } elseif (is_array($deps) && isset($deps[$name])) {
            $full = $deps[$name];
        } else {
            $full = $name;
        }

        $lower = strtolower($full);

        // todo shared or not ?
        if (isset($this->widgets[$lower])) {
            return $this->widgets[$lower];
        }

        if (isset($this->widgetMap[$name])) {
            $class = $this->widgetMap[$name];
        } else {
            $class = 'Qwin\\' . ucfirst($name);
        }

        if (class_exists($class)) {
            $options = $this->config($full);

            if (null === $options && $this->config($name)) {
                throw new \InvalidArgumentException(sprintf('Config name "%s" not found', $full));
            }

            $options = array('widgetManager' => $this) + (array) $options;

            return $this->widgets[$lower] = new $class($options);
        }

        $traces = debug_backtrace();

        require_once 'Exception.php';

        // called by class ?
        if (isset($traces[1]) && '__get' == $traces[1]['function'] && $name == $traces[1]['args'][0]) {
            throw new Exception(sprintf('Property or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[1]['args'][0], $class, $traces[1]['file'], $traces[1]['line']));
        } elseif (isset($traces[3]) && $name == $traces[3]['function']) {
            // for call_user_func
            $file = isset($traces[3]['file']) ? $traces[3]['file'] : $traces[4]['file'];
            $line = isset($traces[3]['line']) ? $traces[3]['line'] : $traces[4]['line'];
            throw new \BadMethodCallException(sprintf('Method "%s->%2$s" or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[3]['class'], $traces[3]['function'], $class, $file, $line));
        } else {
            // Call to undefined method class::method
            // Undefined property: class::$property
            throw new \BadMethodCallException(sprintf('Property or method "%s" not defined', $name));
        }
    }

    /**
     * Remove the widget instance by the given name
     *
     * @param  string  $name The name of widget
     * @return bool
     */
    public function removeWidget($name)
    {
        $lower = strtolower($name);

        if (isset($this->widgets[$lower])) {
            unset($this->widgets[$lower]);
            return true;
        }
        
        return false;
    }

    /**
     * Check if the widget exists by the given name
     * 
     * @param string $name The name of widget
     * @return bool
     */
    public function hasWidget($name)
    {
        if (isset($this->widgetMap[$name])) {
            $class = $this->widgetMap[$name];
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
    public function setAutoload($enable)
    {
        if ($enable) {
            spl_autoload_register(array($this, 'autoload'));
        } else {
            spl_autoload_unregister(array($this, 'autoload'));
        }
        $this->autoload = (bool) $enable;

        return $this;
    }

    /**
     * Set autoload directories for autoload method
     *
     * @param  string|array        $dirs
     * @return \Qwin\WidgetManager
     */
    public function setAutoloadDirs($dirs)
    {
        !is_array($dirs) && $dirs = (array) $dirs;
        foreach ($dirs as &$dir) {
            $dir = realpath($dir) . DIRECTORY_SEPARATOR;
        }
        // the autoload directories will always contain the directory of the class file
        $dirs[] = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

        $this->autoloadDirs = array_unique($dirs);

        return $this;
    }

    /**
     * Set the ini options
     *
     * @param  array               $inis
     * @return \Qwin\WidgetManager
     */
    public function setInis($inis)
    {
        foreach ($inis as $key => $value) {
            ini_set($key, $value);
        }

        return $this;
    }
}
