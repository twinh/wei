<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget
{
    /**
     * @see Widget\Base
     */
    require_once 'Base.php';

    /**
     * The root widget and widget container
     *
     * @author      Twin Huang <twinhuang@qq.com>
     */
    class Widget extends Base
    {
        /**
         * Version
         */
        const VERSION = '0.9.4';

        /**
         * The instances of widget container
         *
         * @var Widget[]
         */
        protected static $instances = array();

        /**
         * Whether in debug mode or not
         *
         * @var bool
         */
        protected $debug = true;

        /**
         * An array contains the instanced widget objects
         *
         * @var Base[]
         */
        protected $widgets = array();

        /**
         * The configurations for all widgets
         *
         * @var array
         */
        protected $configs = array();

        /**
         * The php configuration options that will be set when widget container constructing
         *
         * @var array
         * @see http://www.php.net/manual/en/ini.php
         * @see http://www.php.net/manual/en/function.ini-set.php
         */
        protected $inis = array();

        /**
         * The directories for autoload
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
        protected $aliases = array();

        /**
         * The widgets that will be instanced after widget container constructed
         *
         * @var array
         */
        protected $preload = array(
            'is'
        );

        /**
         * The import configuration
         *
         * Format:
         * array(
         *     array(
         *         'dir' => 'lib/Widget/Validator'
         *         'namespace' => 'Widget\Validator'
         *         'format' => 'is%s',
         *         'autoload' => false
         *     )
         * )
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
         * Instance widget container
         *
         * @param array $config
         */
        public function __construct(array $config = array())
        {
            // Set configurations for all widget
            $this->setConfig($config);

            $this->widgets['widget'] = $this->widget = $this;

            // Set all options
            $options = get_object_vars($this);
            if (isset($this->configs['widget'])) {
                $options = array_merge($options, $this->configs['widget']);
            }
            $this->setOption($options);

            // Instance preload widgets
            foreach ((array)$this->preload as $widgetName) {
                $this->get($widgetName);
            }
        }

        /**
         * Get widget container instance
         *
         * @param array $config                 The array or file configuration
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
                static::$instances[$name]->setConfig($config);
            }

            return static::$instances[$name];
        }

        /**
         * Reset the internal widget container instance
         *
         * @param string|null $name The name of the instance, if $name is null, reset all instances
         * @return bool
         */
        public static function reset($name = null)
        {
            if (is_null($name)) {
                static::$instances = array();
                return true;
            } elseif (isset(static::$instances[$name])) {
                unset(static::$instances[$name]);
                return true;
            } else {
                return false;
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
                // Allow empty class prefix
                if (!$prefix || 0 === strpos($class, $prefix)) {
                    if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $class)) {
                        require_once $file;
                        return true;
                    }
                }
            }

            return false;
        }

        /**
         * Set widget's configuration
         *
         * @param string|array $name
         * @param mixed $value
         * @return Widget
         */
        public function setConfig($name, $value = null)
        {
            // Set array configurations
            if (is_array($name)) {
                foreach ($name as $key => $value) {
                    $this->setConfig($key, $value);
                }
                return $this;
            }

            // Set one configuration
            $names = explode(':', $name);
            $first = $names[0];
            $configs = &$this->configs;

            foreach ($names as $name) {
                if (!is_array($configs)) {
                    $configs = array();
                }
                if (!isset($configs[$name])) {
                    $configs[$name] = array();
                }
                $configs = &$configs[$name];
            }

            // Merge only first child node
            if (is_array($configs) && is_array($value)) {
                $configs = $value + $configs;
            } else {
                $configs = $value;
            }

            /**
             * Automatically create dependence map when configuration key contains "."
             *
             * $this->configs = array(
             *    'mysql.db' => array(),
             * );
             * =>
             * $this->deps['mysqlDb'] = 'mysql.db';
             */
            if (false !== strpos($first, '.')) {
                $parts = explode('.', $first, 2);
                $widgetName = $parts[0] . ucfirst($parts[1]);
                if (!isset($this->deps[$widgetName])) {
                    $this->deps[$widgetName] = $first;
                }
            }

            // Set options for existing widget
            if (isset($this->widgets[$first])) {
                $this->widgets[$first]->setOption($value);
            }

            return $this;
        }

        /**
         * Get widget's configuration
         *
         * @param string $name The name of configuration
         * @param mixed $default The default value if configuration not found
         * @return mixed
         */
        public function getConfig($name, $default = null)
        {
            if (false === strpos($name, ':')) {
                return isset($this->configs[$name]) ? $this->configs[$name] : $default;
            }

            $configs = &$this->configs;
            foreach (explode(':', $name) as $key) {
                if (is_array($configs) && isset($configs[$key])) {
                    $configs = &$configs[$key];
                } else {
                    return $default;
                }
            }
            return $configs;
        }

        /**
         * Get a widget object and call its "__invoke" method
         *
         * @param string $name  The name of the widget
         * @param array $args   The arguments for "__invoke" method
         * @param array $deps   The dependent configuration
         * @return mixed
         */
        public function invoke($name, array $args = array(), $deps = array())
        {
            $widget = $this->get($name, $deps);

            return call_user_func_array(array($widget, '__invoke'), $args);
        }

        /**
         * Get a widget object
         *
         * @param string $name  The name of the widget, without class prefix "Widget\"
         * @param array $options The option properties for widget
         * @param array $deps The dependent configuration
         * @throws \BadMethodCallException
         * @return Base
         */
        public function get($name, array $options = array(), array $deps = array())
        {
            // Resolve the widget name in dependent configuration
            if (isset($deps[$name])) {
                $name = $deps[$name];
            }

            if (isset($this->deps[$name])) {
                $name = $this->deps[$name];
            }

            if (isset($this->widgets[$name])) {
                return $this->widgets[$name];
            }

            // Resolve the real widget name and the config name($full)
            $full = $name;
            if (false !== ($pos = strpos($name, '.'))) {
                $name = substr($name, $pos + 1);
            }

            // Get the widget class and instance
            $class = $this->getClass($name);
            if (class_exists($class)) {
                // Trigger the construct callback
                $this->construct && call_user_func($this->construct, $name, $full);

                // Load the widget configuration and make sure "widget" option at first
                $options = array('widget' => $this) + $options + (array)$this->getConfig($full);

                $this->widgets[$full] = new $class($options);

                // Trigger the constructed callback
                $this->constructed && call_user_func($this->constructed, $this->widgets[$full], $name, $full);

                return $this->widgets[$full];
            }

            // Build the error message
            $traces = debug_backtrace();

            // $widget->notFound()
            if (isset($traces[3]) && $name == $traces[3]['function']) {
                // For call_user_func/call_user_func_array
                $file = isset($traces[3]['file']) ? $traces[3]['file'] : $traces[4]['file'];
                $line = isset($traces[3]['line']) ? $traces[3]['line'] : $traces[4]['line'];
                throw new \BadMethodCallException(sprintf('Method "%s->%2$s" or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[3]['class'], $traces[3]['function'], $class, $file, $line));
            // $widget->notFound
            } elseif (isset($traces[1]) && '__get' == $traces[1]['function'] && $name == $traces[1]['args'][0]) {
                throw new \BadMethodCallException(sprintf('Property or widget "%s" (class "%s") not found, called in file "%s" at line %s', $traces[1]['args'][0], $class, $traces[1]['file'], $traces[1]['line']));
            // $widget->get('notFound');
            } else {
                throw new \BadMethodCallException(sprintf('Property or method "%s" not found', $name));
            }
        }

        /**
         * Initialize a new instance of widget, with the specified name
         *
         * @param string $name The name of the widget
         * @param array $options The option properties for widget
         * @param array $deps The dependent configuration
         * @return Base The widget object
         */
        public function newInstance($name, array $options = array(), array $deps = array())
        {
            $name .= uniqid() . '.' . $name;
            return $this->widget->get($name, $options, $deps);
        }

        /**
         * Add a widget
         *
         * @param string $name The name of widget
         * @param Base $widget The widget object
         * @return Widget
         */
        public function set($name, Base $widget)
        {
            $this->$name = $this->widgets[$name] = $widget;
            return $this;
        }

        /**
         * Remove the widget instance by the given name
         *
         * @param  string  $name The name of widget
         * @return bool
         */
        public function remove($name)
        {
            if (isset($this->widgets[$name])) {
                unset($this->widgets[$name]);
                if (isset($this->$name) && $this->$name instanceof Base) {
                    unset($this->$name);
                }
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
            if (isset($this->aliases[$name])) {
                $class = $this->aliases[$name];
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
         * Set debug flag
         *
         * @param $bool
         * @return Widget
         */
        public function setDebug($bool)
        {
            $this->debug = (bool) $bool;
            return $this;
        }

        /**
         * Whether in debug mode or not
         *
         * @return bool
         */
        public function inDebug()
        {
            return $this->debug;
        }

        /**
         * Whether enable autoload or not
         *
         * @param bool $enable
         * @return Widget
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
         * @param array $map
         * @return Widget
         */
        public function setAutoloadMap(array $map)
        {
            foreach ($map as &$dir) {
                $dir = realpath($dir);
            }

            // The autoload directories will always contain the widget directory
            $map['Widget'] = dirname(dirname(__FILE__));

            $this->autoloadMap = $map;

            return $this;
        }

        /**
         * Sets the value of PHP configuration options
         *
         * @param array $inis
         * @return Widget
         */
        public function setInis(array $inis)
        {
            $this->inis = $inis + $this->inis;
            foreach ($inis as $key => $value) {
                ini_set($key, $value);
            }
            return $this;
        }

        /**
         * Merge widget aliases
         *
         * @param array $aliases
         * @return $this
         */
        public function setAliases(array $aliases)
        {
            $this->aliases = $aliases + $this->aliases;
            return $this;
        }

        /**
         * Set widget alias
         *
         * @param string $name The name of widget
         * @param string $class The class that the widget reference to
         * @return Widget
         */
        public function setAlias($name, $class)
        {
            $this->aliases[$name] = $class;
            return $this;
        }

        /**
         * Import the class in the given directory as widget
         *
         * @param string $dir The directory for class
         * @param string $namespace The prefix namespace of the class
         * @param null $format The widget name format, eg 'is%s'
         * @param bool $autoload Whether add namespace and directory to `autoloadMap` or nor
         * @throws \InvalidArgumentException When the first parameter is not a directory
         * @return Widget
         */
        public function import($dir, $namespace, $format = null, $autoload = false)
        {
            if (!is_dir($dir)) {
                throw new \InvalidArgumentException(sprintf('Fail to import widgets from non-exists directory "%s"', $dir));
            }

            if ($autoload) {
                $this->autoloadMap[$namespace] = dirname($dir);
            }

            $files = glob($dir . '/*.php') ?: array();
            foreach ($files as $file) {
                $class = substr(basename($file), 0, -4);
                $name = $format ? sprintf($format, $class) : $class;
                $this->aliases[lcfirst($name)] = $namespace . '\\' . $class;
            }

            return $this;
        }

        /**
         * Set import
         *
         * @param array $import
         * @return Widget
         */
        public function setImport(array $import = array())
        {
            foreach ($import as $option) {
                $option += array(
                    'dir' => null,
                    'namespace' => null,
                    'format' => null,
                    'autoload' => true,
                );
                $this->import($option['dir'], $option['namespace'], $option['format'], $option['autoload']);
            }
            return $this;
        }
    }
}

/**
 * Define function in global namespace
 */
namespace
{
    /**
     * Get widget container instance
     *
     * @param array $config                 The array or file configuration
     * @param string $name                  The name of the instance
     * @return Widget\Widget
     */
    function widget($config = array(), $name = 'default')
    {
        return Widget\Widget::create($config, $name);
    }

    /**
     * Get widget container instance
     *
     * @return \Widget\Widget
     */
    function wei()
    {
        return Widget\Widget::create();
    }
}