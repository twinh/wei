<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei {
    use ReflectionException;

    /**
     * @see Base
     */
    require_once 'Base.php';

    /**
     * The service container
     *
     * @author      Twin Huang <twinhuang@qq.com>
     */
    class Wei extends Base
    {
        /**
         * Version
         */
        public const VERSION = '0.17.6';

        /**
         * The configurations for all objects
         *
         * @var array
         */
        protected $configs = [];

        /**
         * The name of current application
         *
         * @var string
         */
        protected $namespace;

        /**
         * Whether in debug mode or not
         *
         * @var bool
         */
        protected $debug = true;

        /**
         * The PHP configuration options that will be set when the service container constructing
         *
         * @var array
         * @see http://www.php.net/manual/en/ini.php
         * @see http://www.php.net/manual/en/function.ini-set.php
         */
        protected $inis = [];

        /**
         * Whether enable class autoload or not
         *
         * @var bool
         */
        protected $autoload = true;

        /**
         * The directories for autoload
         *
         * @var array
         */
        protected $autoloadMap = [];

        /**
         * The service name to class name map
         *
         * @var array
         */
        protected $aliases = [];

        /**
         * The service provider map
         *
         * @var array
         */
        protected $providers = [];

        /**
         * The import configuration
         *
         * Format:
         * array(
         *     array(
         *         'dir' => 'lib/Wei/Validator',
         *         'namespace' => 'Wei\Validator',
         *         'format' => 'is%s',
         *         'autoload' => false
         *     ),
         *     array(
         *         'dir' => 'src/MyProject/Wei',
         *         'namespace' => 'MyProject\Wei',
         *         'format' => '%s',
         *         'autoload' => true
         *     )
         * );
         * @var array
         */
        protected $import = [];

        /**
         * The callback executes *before* service constructed
         *
         * @var callable
         */
        protected $beforeConstruct;

        /**
         * The callback executes *after* service constructed
         *
         * @var callable
         */
        protected $afterConstruct;

        /**
         * The services that will be instanced after service container constructed
         *
         * @var array
         */
        protected $preload = [];

        /**
         * An array contains the instanced services
         *
         * @var Base[]
         */
        protected $services = [];

        /**
         * Whether to check if the service method exists
         *
         * @var bool
         */
        protected $checkServiceMethod = false;

        /**
         * The current service container
         *
         * @var Wei
         */
        protected static $container;

        /**
         * Instance service container
         *
         * @param array $config
         */
        public function __construct(array $config = [])
        {
            // Set configurations for all services
            $this->setConfig($config);

            $this->set('wei', $this);
            $this->wei = $this;

            // Set all options
            $options = get_object_vars($this);
            if (isset($this->configs['wei'])) {
                $options = array_merge($options, $this->configs['wei']);
            }
            $this->setOption($options);
        }

        /**
         * Get a service object by the given name
         *
         * @param string $name The name of service
         * @return Base
         */
        public function __get($name)
        {
            // The service has been conditionally cached in the `get` method,
            // it should not call `__set` to cache the service here
            return $this->get($name);
        }

        /**
         * Add a service to the service container
         *
         * @param string $name The name of service
         * @param object $service The service service
         * @return $this
         */
        public function __set($name, $service)
        {
            return $this->set($name, $service);
        }

        /**
         * Get the service container instance
         *
         * @param array|string $config The array or file configuration
         * @return $this
         * @throws \InvalidArgumentException    When the configuration parameter is not array or file
         */
        public static function getContainer($config = [])
        {
            // Most of time, it's called after instanced and without any arguments
            if (!$config && static::$container) {
                return static::$container;
            }

            switch (true) {
                case is_array($config):
                    break;

                case is_string($config) && file_exists($config):
                    $config = (array) require $config;
                    break;

                default:
                    throw new \InvalidArgumentException('Configuration should be array or file', 1010);
            }

            if (!isset(static::$container)) {
                static::$container = new static($config);
            } else {
                static::$container->setConfig($config);
            }
            return static::$container;
        }

        /**
         * Set the service container
         *
         * @param Wei $container
         */
        public static function setContainer(?self $container = null)
        {
            static::$container = $container;
        }

        /**
         * Autoload the PSR-0 class
         *
         * @param string $class the name of the class
         * @return bool
         */
        public function autoload($class)
        {
            $class = strtr($class, ['_' => \DIRECTORY_SEPARATOR, '\\' => \DIRECTORY_SEPARATOR]) . '.php';

            foreach ($this->autoloadMap as $prefix => $dir) {
                // Autoload class from relative path like PSR-4 when prefix starts with "\"
                if (isset($prefix[0]) && '\\' == $prefix[0] && 0 === strpos($class, ltrim($prefix, '\\'))) {
                    $file = $dir . \DIRECTORY_SEPARATOR . substr($class, strlen($prefix));
                    if (file_exists($file)) {
                        require_once $file;
                        return true;
                    }
                }

                // Allow empty class prefix
                if (!$prefix || 0 === strpos($class, $prefix)) {
                    if (file_exists($file = $dir . \DIRECTORY_SEPARATOR . $class)) {
                        require_once $file;
                        return true;
                    }
                }
            }

            return false;
        }

        /**
         * Get a service and call its "__invoke" method
         *
         * @param string $name The name of the service
         * @param array $args The arguments for "__invoke" method
         * @param array $providers The service providers map
         * @return mixed
         */
        public function invoke($name, array $args = [], $providers = [])
        {
            $service = $this->get($name, $providers);
            return call_user_func_array([$service, '__invoke'], $args);
        }

        /**
         * Get a service
         *
         * @param string $name The name of the service, without class prefix "Wei\"
         * @param array $options The option properties for service
         * @param array $providers The dependent configuration
         * @param bool $new Whether to create a new instance
         * @return Base
         */
        public function get($name, array $options = [], array $providers = [], $new = false)
        {
            // Resolve the service name in dependent configuration
            if (isset($providers[$name])) {
                $name = $providers[$name];
            }

            if (isset($this->providers[$name])) {
                $name = $this->providers[$name];
            }

            if (!$new && isset($this->services[$name])) {
                return $this->services[$name];
            }

            // Resolve the real service name and the config name($full)
            $full = $name;
            if (false !== ($pos = strpos($name, ':'))) {
                $name = substr($name, $pos + 1);
            }

            // Get the service class and instance
            $class = $this->getClass($name);
            if (class_exists($class)) {
                // Trigger the before construct callback
                $this->beforeConstruct && call_user_func($this->beforeConstruct, $this, $full, $name);

                // Load the service configuration and make sure "wei" option at first
                $options = ['wei' => $this] + $options + (array) $this->getConfig($full);

                $service = new $class($options);
                if (!$new && (!method_exists($class, 'isCreateNewInstance') || !$class::isCreateNewInstance())) {
                    $this->services[$full] = $service;
                }

                // Trigger the after construct callback
                $this->afterConstruct && call_user_func($this->afterConstruct, $this, $full, $name, $service);

                return $service;
            }

            // Build the error message
            $traces = debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

            // Example: $wei->notFound(), call_user_func([$wei, 'notFound'])
            if (isset($traces[3]) && '__call' === $traces[3]['function'] && $name === $traces[3]['args'][0]) {
                // For call_user_func/call_user_func_array
                $file = $traces[3]['file'] ?? $traces[4]['file'];
                $line = $traces[3]['line'] ?? $traces[4]['line'];
                throw new \BadMethodCallException(sprintf(
                    'Method "%s->%s" (class "%s") not found, called in file "%s" at line %s',
                    get_class($traces[3]['object']),
                    $traces[3]['args'][0],
                    $class,
                    $file,
                    $line
                ), 1014);
            }

            // Example: $wei->notFound
            if (isset($traces[1]) && '__get' === $traces[1]['function'] && $name === $traces[1]['args'][0]) {
                throw new \BadMethodCallException(sprintf(
                    'Property or object "%s" (class "%s") not found, called in file "%s" at line %s',
                    $traces[1]['args'][0],
                    $class,
                    $traces[1]['file'],
                    $traces[1]['line']
                ), 1012);
            }

            // Example: $wei->get('notFound');
            throw new \BadMethodCallException(sprintf('Property or method "%s" not found', $name), 1013);
        }

        /**
         * Returns a callable for "__callStatic" method
         *
         * @param string $class
         * @param string $method
         * @return callable
         * @throws ReflectionException
         */
        public static function staticCaller($class, $method)
        {
            $wei = static::getContainer();

            if ($wei->checkServiceMethod && !$wei->isServiceMethod($class, $method)) {
                throw new \BadMethodCallException(sprintf('Service method "%s" not found', $method));
            }

            $name = $wei->getServiceName($class);
            if ($wei->has($name)) {
                $instance = $wei->get($name);
            } else {
                // Not a registered service, simply create a new instance
                $instance = new $class(['wei' => $wei]);
            }

            return [$instance, $method];
        }

        /**
         * Returns a callable for "__call" method
         *
         * @param Base $service
         * @param string $method
         * @return callable
         * @throws ReflectionException
         */
        public function caller(Base $service, $method)
        {
            if ($this->checkServiceMethod) {
                if ($this->isServiceMethod(get_class($service), $method)) {
                    return [$service, $method];
                }
            } elseif (method_exists($service, $method)) {
                // NOTE: Calling `Class::privateMethod()` or the non-existent `parent::$method()`
                // will be caught by `__call`, which will lead to an endless loop and eventually run out of memory
                // To debug these cases, please set `checkServiceMethod` to `true`
                return [$service, $method];
            }
            return $service->{$method};
        }

        /**
         * Returns all instanced services
         *
         * @return Base[]
         */
        public function getServices()
        {
            return $this->services;
        }

        /**
         * Check if the service is instanced
         *
         * @param string $name The name of service
         * @return bool
         */
        public function isInstanced($name)
        {
            return isset($this->services[$name]);
        }

        /**
         * Initialize a new instance of service, with the specified name
         *
         * @param string $name The name of the service
         * @param array $options The option properties for service
         * @param array $providers The dependent configuration
         * @return Base The instanced service
         */
        public function newInstance($name, array $options = [], array $providers = [])
        {
            return $this->wei->get($name, $options, $providers, true);
        }

        /**
         * Add a service to the service container
         *
         * @param string $name The name of service
         * @param object $service The object of service
         * @return $this
         */
        public function set($name, $service)
        {
            $this->services[$name] = $service;
            return $this;
        }

        /**
         * Remove the service by the specified name
         *
         * @param string $name The name of service
         * @return bool
         */
        public function remove($name)
        {
            if (isset($this->services[$name])) {
                unset($this->services[$name]);
                return true;
            }
            return false;
        }

        /**
         * Get the service class by the given name
         *
         * @param string $name The name of service
         * @return string
         */
        public function getClass($name)
        {
            if (isset($this->aliases[$name])) {
                $class = $this->aliases[$name];
            } else {
                $class = 'Wei\\' . ucfirst($name);
            }
            return $class;
        }

        /**
         * Check if the service exists by the specified name, if the service exists,
         * returns the full class name, else return false
         *
         * @param string $name The name of service
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
         * @return $this
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
        public function isDebug()
        {
            return $this->debug;
        }

        /**
         * Whether enable autoload or not
         *
         * @param bool $enable
         * @return $this
         */
        public function setAutoload($enable)
        {
            $this->autoload = (bool) $enable;
            call_user_func(
                $enable ? 'spl_autoload_register' : 'spl_autoload_unregister',
                [$this, 'autoload']
            );
            return $this;
        }

        /**
         * Set autoload directories for autoload method
         *
         * @param array $map
         * @return $this
         * @throws \InvalidArgumentException
         */
        public function setAutoloadMap(array $map)
        {
            foreach ($map as &$dir) {
                if (!is_dir($dir)) {
                    throw new \InvalidArgumentException(sprintf('Directory "%s" for autoloading is not found', $dir));
                }
                $dir = realpath($dir);
            }

            // Automatic add PSR-4 autoloading for "\Wei" namespace
            $map['\Wei'] = __DIR__;

            $this->autoloadMap = $map;
            return $this;
        }

        /**
         * Sets the value of PHP configuration options
         *
         * @param array $inis
         * @return $this
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
         * Merge service aliases
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
         * Set service alias
         *
         * @param string $name The name of service
         * @param string $class The class that the service reference to
         * @return $this
         */
        public function setAlias($name, $class)
        {
            $this->aliases[$name] = $class;
            return $this;
        }

        /**
         * Import classes in the specified directory as services
         *
         * @param string $dir The directory to search class
         * @param string $namespace The prefix namespace of the class
         * @param null $format The service name format, eg 'is%s'
         * @param bool $autoload Whether add namespace and directory to `autoloadMap` or nor
         * @return $this
         * @throws \InvalidArgumentException When the first parameter is not a directory
         */
        public function import($dir, $namespace, $format = null, $autoload = false)
        {
            if (!is_dir($dir)) {
                throw new \InvalidArgumentException(sprintf(
                    'Fail to import classes from non-exists directory "%s"',
                    $dir
                ), 1014);
            }

            if ($autoload) {
                $this->autoloadMap[$namespace] = dirname($dir);
            }

            $files = glob($dir . '/*.php') ?: [];
            foreach ($files as $file) {
                $class = substr(basename($file), 0, -4);
                $name = $format ? sprintf($format, $class) : $class;
                $this->aliases[lcfirst($name)] = $namespace . '\\' . $class;
            }

            return $this;
        }

        /**
         * Set the name of current application
         *
         * @param string $namespace
         * @return $this
         */
        public function setNamespace($namespace)
        {
            $this->namespace = $namespace;
            return $this;
        }

        /**
         * Returns the name of current application
         *
         * @return string
         */
        public function getNamespace()
        {
            return $this->namespace;
        }

        /**
         * @param bool $checkServiceMethod
         * @return $this
         */
        public function setCheckServiceMethod(bool $checkServiceMethod)
        {
            $this->checkServiceMethod = $checkServiceMethod;
            return $this;
        }

        /**
         * @return bool
         */
        public function isCheckServiceMethod()
        {
            return $this->checkServiceMethod;
        }

        /**
         * @param string $class
         * @param $method
         * @return bool|mixed
         * @throws ReflectionException
         * @noinspection UselessReturnInspection
         */
        public function isServiceMethod($class, $method)
        {
            static $cache = [];
            $exists = &$cache[$class][$method];

            if (isset($exists)) {
                return $exists;
            }

            if (!method_exists($class, $method)) {
                return $exists = false;
            }

            $ref = new \ReflectionMethod($class, $method);
            if (!$ref->isProtected()) {
                return $exists = false;
            }

            return $exists = false !== strpos($ref->getDocComment(), "* @svc\n");
        }

        /**
         * Receive the base name of specified class
         *
         * @param string $class
         * @return string
         */
        public function getServiceName($class)
        {
            $parts = explode('\\', $class);
            return lcfirst(end($parts));
        }

        /**
         * Set service's configuration
         *
         * @param string|array $name
         * @param mixed $value
         * @return $this
         * @svc
         */
        protected function setConfig($name, $value = null)
        {
            // Set array configurations
            if (is_array($name)) {
                foreach ($name as $key => $value) {
                    $this->setConfig($key, $value);
                }
                return $this;
            }

            // Set one configuration
            $names = explode('.', $name);
            $first = $names[0];
            $configs = &$this->configs;

            foreach ($names as $name) {
                if (!is_array($configs)) {
                    $configs = [];
                }
                if (!isset($configs[$name])) {
                    $configs[$name] = [];
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
             *    'mysql:db' => array(),
             * );
             * =>
             * $this->providers['mysqlDb'] = 'mysql:db';
             */
            if (false !== strpos($first, ':')) {
                $parts = explode(':', $first, 2);
                $serviceName = $parts[0] . ucfirst($parts[1]);
                if (!isset($this->providers[$serviceName])) {
                    $this->providers[$serviceName] = $first;
                }
            }

            // Set options for existing service
            if (isset($this->services[$first])) {
                $this->services[$first]->setOption($value);
            }

            return $this;
        }

        /**
         * Get services' configuration
         *
         * @param string $name The name of configuration
         * @param mixed $default The default value if configuration not found
         * @return mixed
         * @svc
         */
        protected function getConfig($name = null, $default = null)
        {
            if (null === $name) {
                return $this->configs;
            }

            if (false === strpos($name, '.')) {
                return isset($this->configs[$name]) ? $this->configs[$name] : $default;
            }

            $configs = &$this->configs;
            foreach (explode('.', $name) as $key) {
                if (is_array($configs) && isset($configs[$key])) {
                    $configs = &$configs[$key];
                } else {
                    return $default;
                }
            }
            return $configs;
        }

        /**
         * Remove services' configuration
         *
         * @param string $name
         * @return $this
         * @svc
         */
        protected function removeConfig(string $name): self
        {
            $names = explode('.', $name);
            $last = array_pop($names);

            $configs = &$this->configs;

            foreach ($names as $name) {
                if (!is_array($configs)) {
                    $configs = [];
                }
                $configs = &$configs[$name];
            }
            unset($configs[$last]);
            return $this;
        }

        /**
         * Get service by class name
         *
         * @template T
         * @param string $class
         * @phpstan-param class-string<T> $class
         * @return Base|T
         * @svc
         */
        protected function getBy(string $class): Base
        {
            return $this->get($this->getServiceName($class));
        }

        /**
         * Set import services
         *
         * @param array $import
         */
        protected function setImport(array $import = [])
        {
            $this->import = $import + $this->import;
            foreach ($import as $option) {
                $option += [
                    'dir' => null,
                    'namespace' => null,
                    'format' => null,
                    'autoload' => false,
                ];
                $this->import($option['dir'], $option['namespace'], $option['format'], $option['autoload']);
            }
        }

        /**
         * Merge the dependence map
         *
         * @param array $providers
         */
        protected function setProviders(array $providers)
        {
            $this->providers = $providers + $this->providers;
        }

        /**
         * Instance preload services
         *
         * @param array $preload
         */
        protected function setPreload(array $preload)
        {
            $this->preload = array_merge($this->preload, $preload);
            foreach ($preload as $service) {
                $this->set($service, $this->get($service));
            }
        }

        /**
         * Merge service objects
         *
         * @param array $services
         */
        protected function setServices(array $services)
        {
            $this->services = $services + $this->services;
        }
    }
}

/**
 * Define function in global namespace
 */

namespace {
    /**
     * Get the service container instance
     *
     * @return Wei\Wei
     */
    function wei()
    {
        return call_user_func_array(['Wei\Wei', 'getContainer'], func_get_args());
    }
}
