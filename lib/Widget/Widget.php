<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
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
 */
class Widget extends WidgetProvider
{
    /**
     * Version
     */
    const VERSION = '0.9.1';

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
     * Whether enable class autoload or not
     *
     * @var bool
     */
    protected $autoload = true;

    /**
     * The widget name to class name map
     *
     * @var array
     */
    protected $alias = array();

    /**
     * The widgets that will be instanced after widget manager constructed
     *
     * @var array
     */
    protected $initWidgets = array();

    /**
     * @var array
     */
    protected $import = array();

    /**
     * The callback executes *before* widget constructed
     *
     * @var callable
     */
    protected $construct;

    /**
     * The callback executes *after* widget constructed
     *
     * @var callable
     */
    protected $constructed;

    /**
     * Instance widget manager
     *
     * @param array $config
     * @return void
     */
    public function __construct(array $config = array())
    {
        // set configurations for all widget
        $this->config = $config;

        $this->widget = $this;

        // set all options
        $options = get_object_vars($this);
        if (isset($this->config['widget'])) {
            $options = $this->config['widget'] + $options;
        }
        $this->option($options);

        // instance initial widgets
        foreach ((array)$this->initWidgets as $widgetName) {
            $this->get($widgetName, null, $this);
        }
    }

    /**
     * Get widget manager instance
     *
     * @param array $config          The array or file configuration
     * @param string $name                  The name of the instance
     * @return Widget
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
     * @return object
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
                if (isset($this->widgets[$name])) {
                    $this->widgets[$name]->option(func_get_arg(1));
                } else {
                    $temp[$name] = func_get_arg(1);
                }
                return $this;
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
     * @param string $name  The name of the widget
     * @param array $args   The arguments for "__invoke" method
     * @param array $deps    The dependent configuration
     * @return mixed
     */
    public function invoke($name, array $args, $deps = array())
    {
        $widget = $this->get($name, $deps);

        return call_user_func_array(array($widget, '__invoke'), $args);
    }

    /**
     * Get a widget object
     *
     * @param string $name  The name of the widget, without class prefix "Widget\"
     * @param array $deps The dependent configuration
     * @return object The widget object
     */
    public function get($name, $deps = array())
    {
        // Resolve the widget name in dependent configuration
        if (isset($deps[$name])) {
            $name = $deps[$name];
        }

        $lower = strtolower($name);
        if (isset($this->widgets[$lower])) {
            return $this->widgets[$lower];
        }

        // Resolve the real widget name and the config name($full)
        $full = $name;
        if (false !== ($pos = strpos($name, '.'))) {
            $name = substr($name, 0, $pos);
        }

        // Get the widget class and instance
        $class = $this->getClass($name);
        if (class_exists($class)) {
            // @see Widgetable::__invoke
            if (!method_exists($class, '__invoke')) {
                throw new \BadMethodCallException(sprintf('Method "__invoke" not found in widget "%s"', $class));
            }

            // Trigger the construct callback
            $this->construct && call_user_func($this->construct, $name, $full);

            // FIXME widget option should be set first
            // Load the widget configuration
            $options = (array)$this->config($full);
            $options['widget'] = $this;

            $this->widgets[$lower] = new $class($options);

            // Trigger the constructed callback
            $this->constructed && call_user_func($this->constructed, $this->widgets[$lower], $name, $full);

            return $this->widgets[$lower];
        }

        // Build the error message
        $traces = debug_backtrace();

        if (isset($traces[3]) && $name == $traces[3]['function']) {
            // for call_user_func
            $file = isset($traces[3]['file']) ? $traces[3]['file'] : $traces[4]['file'];
            $line = isset($traces[3]['line']) ? $traces[3]['line'] : $traces[4]['line'];
            throw new \BadMethodCallException(sprintf('Method "%s->%2$s" or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[3]['class'], $traces[3]['function'], $class, $file, $line));
        // called by class ?
        } elseif (isset($traces[1]) && '__get' == $traces[1]['function'] && $name == $traces[1]['args'][0]) {
            //require_once 'Exception.php';
            throw new Exception(sprintf('Property or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[1]['args'][0], $class, $traces[1]['file'], $traces[1]['line']));
        } else {
            // Call to undefined method class::method
            // Undefined property: class::$property
            throw new \BadMethodCallException(sprintf('Property or method "%s" not defined', $name));
        }
    }

    /**
     * Add a widget
     *
     * @param string $name The name of widget
     * @param \Widget\Widgetable $widget The widget object
     */
    public function set($name, Widgetable $widget)
    {
        $this->widgets[$name] = $widget;
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
     * Get the widget class by the given name
     *
     * @param string $name The name of widget
     * @return string
     */
    public function getClass($name)
    {
        if (isset($this->alias[$name])) {
            $class = $this->alias[$name];
        } else {
            $class = 'Widget\\' . ucfirst($name);
        }

        return $class;
    }

    /**
     * Check if the widget exists by the given name, if the widget exists,
     * returns the full class name, else return false
     *
     * @param string $name The name of widget
     * @return string|false
     */
    public function has($name)
    {
        $class = $this->getClass($name);

        return class_exists($class) ? $class : false;
    }

    /**
     * Whether enable autoload or not
     *
     * @param bool $enable
     */
    public function setAutoload($enable)
    {
        $this->autoload = (bool) $enable;

        call_user_func(
            $enable ? 'spl_autoload_register' : 'spl_autoload_unregister',
            array($this, 'autoload')
        );

        return $this;
    }

    /**
     * Set autoload directories for autoload method
     *
     * @param  string|array        $maps
     * @return Widget
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
     * @return Widget
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
     * @return Widget
     * @throws \InvalidArgumentException When the first parameter is not a directory
     */
    public function import($dir, $namespace, $format = null)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('Parameter 1 should be valid directory');
        }

        $files = glob($dir . '/*.php');

        foreach ($files as $file) {
            $class = substr(basename($file), 0, -4);
            $name = $format ? sprintf($format, $class) : $class;
            $name = strtolower($name[0]) . substr($name, 1);
            $this->alias[$name] = $namespace . '\\' . $class;
        }

        return $this;
    }

    /**
     * Set import
     *
     * @param array $import
     * @return Widget
     */
    public function setImport($import = array())
    {
        foreach ((array)$import as $option) {
            $option += array(
                'dir' => null,
                'namespace' => null,
                'format' => null
            );
            $this->import($option['dir'], $option['namespace'], $option['format']);
        }

        return $this;
    }
}
