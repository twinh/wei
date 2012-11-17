<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @see Widget\WidgetProvider
 */
require_once 'WidgetProvider.php';

/**
 * The root widget and widget manager
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        autoload interaction with composer ?
 * @todo        set first or get first ?
 * @todo        not shared widgets ?
 * @todo        registerWidgets($dir, $namespace, $prefix)
 * @todo        $this->option($options, array('mustCallSetProperty', '...'))
 */
class Widget extends WidgetProvider
{
    /**
     * Version
     */
    const VERSION = '0.8.9';
    
    /**
     * The instances of widget manager
     *
     * @var array
     */
    protected static $instances = array();
    
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
    protected $autoloadMap = array();
    
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
     * @return \Widget\Widget
     */
    public function __construct(array $config = array())
    {
        // set configurations for all widget
        $this->config = $config;
        
        $this->widget = $this;

        // TODO full properties
        // set options for current widget
        if (isset($config['widget'])) {
            $this->option($config['widget']);
        }
        
        $this->setAutoload($this->autoload);
        
        // instance initial widgets
        foreach ($this->initWidgets as $widgetName) {
            $this->get($widgetName, null, $this);
        }
    }

    /**
     * Get widget manager instance
     * 
     * @param array|string $config          The array or file configuration
     * @param string $name                  The name of the instance
     * @return \Widget\Widget         
     * @throws \InvalidArgumentException    When the configuration parameter is not array or file
     */
    public static function create($config = array(), $name = 'default')
    {
        // Most of time, it's called after instanced and without any arguments
        if (!$config && isset(static::$instances[$name])) {
            return static::$instances[$name];
        }
        
        switch (true) {
            case is_array($config):
                break;
            
            case is_string($config) && file_exists($config):
                $config = (array) require $config;
                break;
            
            default:
                throw new \InvalidArgumentException('Configuration should be array or file');
        }
        
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = new static($config);
        } else {
            static::$instances[$name]->config($config);
        }
        
        return static::$instances[$name];
    }
    
    /**
     * Reset the internal static instance
     * 
     * @param string|null $name             The name of the instance, if $name is null, reset all instances
     * @throws \InvalidArgumentException    When instance not found
     */
    public static function reset($name = null)
    {
        if (is_null($name)) {
            static::$instances = array();
        } elseif (isset(static::$instances[$name])) {
            unset(static::$instances[$name]);
        } else {
            throw new \InvalidArgumentException(sprintf('Widget instance "%s" not found', $name));
        }
    }

    /**
     * Autoload the PSR-0 class
     *
     * @param  string $class the name of the class
     * @return bool
     */
    public function autoload($class)
    {
        $class = strtr($class, array('_' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR)) . '.php';

        foreach ($this->autoloadMap as $prefix => $dir) {
            if (0 === strpos($class, $prefix)) {
                if (file_exists($file = $dir . $class)) {
                    require_once $file;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get a widget instance
     *
     * @param  string       $name The name of widget
     * @return \Widget\Widget
     */
    public function __invoke($name)
    {
        return $this->get($name, null, $this);
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
     * Get a widget object and call its "__invoke" method
     *
     * @param  string          $name   The name of widget
     * @param  array           $args   The arguments for "__invoke" method
     * @param  string          $config
     * @return mixed
     * @throws \Widget\Exception When method "__invoke" not found
     */
    public function invoke($name, array $args, $config = null, $deps = array())
    {
        $widget = $this->get($name, $config, $deps);

        if (!method_exists($widget, '__invoke')) {
            return $this->exception('Method "__invoke" not found in widget "' . get_class($widget) . '"');
        }

        return call_user_func_array(array($widget, '__invoke'), $args);
    }

    /**
     * Get a widget instance
     *
     * @param  string $name The name of the widget, without class prefix "Widget\"
     * @return \Widget\Widget
     */
    public function get($name, $config = null, $deps = array())
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
            $class = 'Widget\\' . ucfirst($name);
        }

        if (class_exists($class)) {
            $options = $this->config($full);

            if (null === $options && $this->config($name)) {
                throw new \InvalidArgumentException(sprintf('Config name "%s" not found', $full));
            }

            $options = array('widget' => $this) + (array) $options;

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
    public function remove($name)
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
    public function has($name)
    {
        if (isset($this->widgetMap[$name])) {
            $class = $this->widgetMap[$name];
        } else {
            $class = 'Widget\\' . ucfirst($name);
        }

        return class_exists($class);
    }

    /**
     * Whether enable autoload or not
     *
     * @param bool $enable
     */
    public function setAutoload($enable)
    {
        $this->autoload = (bool) $enable;
        
        if ($enable) {
            spl_autoload_register(array($this, 'autoload'));
        } else {
            spl_autoload_unregister(array($this, 'autoload'));
        }

        return $this;
    }

    /**
     * Set autoload directories for autoload method
     *
     * @param  string|array        $map
     * @return \Widget\Widget
     */
    public function setAutoloadMap($maps)
    {
        !is_array($maps) && $maps = (array) $maps;
        
        foreach ($maps as &$dir) {
            $dir = realpath($dir) . DIRECTORY_SEPARATOR;
        }
        
        // The autoload directories will always contain the widget directory
        $maps['Widget'] = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

        $this->autoloadMap = $maps;
        
        return $this;
    }

    /**
     * Set the ini options
     *
     * @param  array               $inis
     * @return \Widget\Widget
     */
    public function setInis($inis)
    {
        foreach ($inis as $key => $value) {
            ini_set($key, $value);
        }

        return $this;
    }
    
    /**
     * Import the class in the given directory as widget
     * 
     * @param string $dir The directory for class
     * @param string $namespace The prefix namespace of the class 
     * @param string $prefix The widget name prefix
     * @return \Widget\Widget
     * @throws \InvalidArgumentException When the first parameter is not a directory
     */
    public function import($dir, $namespace, $prefix = null)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('Parameter 1 should be valid directory');
        }
        
        $files = glob($dir . '/*.php');
        
        foreach ($files as $file) {
            $class = substr(basename($file), 0, -4);
            $name = $prefix ? $prefix . $class : strtolower($class[0]) . substr($class, 1);
            $this->widgetMap[$name] = $namespace . '\\' . $class;
        }

        return $this;
    }
}
