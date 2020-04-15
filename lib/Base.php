<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use ReflectionException;

/**
 * The base class for all services
 *
 * @author   Twin Huang <twinhuang@qq.com>
 */
abstract class Base
{
    /**
     * Whether to check if the service method exists
     *
     * @var bool
     */
    protected static $checkServiceMethod = false;

    /**
     * The service provider map
     *
     * @var array
     */
    protected $providers = array();

    /**
     * The service container object
     *
     * @var Wei
     */
    protected $wei;

    /**
     * Constructor
     *
     * @param array $options The property options
     * @throws \InvalidArgumentException When option "wei" is not an instance of "Wei\Wei"
     */
    public function __construct(array $options = array())
    {
        if (!isset($options['wei'])) {
            $this->wei = Wei::getContainer();
        } elseif (!$options['wei'] instanceof Wei) {
            throw new \InvalidArgumentException(sprintf('Option "wei" of class "%s" should be an instance of "Wei\Wei"', get_class($this)), 1000);
        }
        $this->setOption($options);
    }

    /**
     * Set option property value
     *
     * @param string|array $name
     * @param mixed $value
     * @return $this
     */
    public function setOption($name, $value = null)
    {
        // Set options
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->setOption($k, $v);
            }
            return $this;
        }

        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            $this->$name = $value;
            return $this;
        }
    }

    /**
     * Returns the value of option
     *
     * @param string $name The name of property
     * @return mixed
     */
    public function getOption($name)
    {
        if (method_exists($this, $method = 'get' . $name)) {
            return $this->$method();
        } else {
            return isset($this->$name) ? $this->$name : null;
        }
    }

    /**
     * @param string $method
     * @return bool
     * @throws ReflectionException
     * @noinspection UselessReturnInspection
     */
    protected static function isServiceMethod(string $method): bool
    {
        static $cache = [];
        $exists = &$cache[static::class][$method];

        if (isset($exists)) {
            return $exists;
        }

        if (!method_exists(static::class, $method)) {
            return $exists = false;
        }

        $ref = new \ReflectionMethod(static::class, $method);
        if (!$ref->isProtected()) {
            return $exists = false;
        }

        return $exists = strpos($ref->getDocComment(), "* @svc\n") !== false;
    }

    /**
     * Call a service method through static call
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws ReflectionException
     */
    public static function __callStatic(string $method, array $args)
    {
        if (static::$checkServiceMethod && !static::isServiceMethod($method)) {
            throw new \BadMethodCallException(sprintf('Service method "%s" not found', $method));
        }

        if (isset(static::$createNewInstance) && static::$createNewInstance) {
            $instance = static::newInstance();
        } else {
            $instance = wei()->get(static::getServiceName());
        }
        return $instance->$method(...$args);
    }

    /**
     * Invoke a service by the given name
     *
     * @param string $name The name of service
     * @param array $args The arguments for the service's __invoke method
     * @return mixed
     * @throws ReflectionException
     */
    public function __call($name, $args)
    {
        if (static::$checkServiceMethod) {
            if (static::isServiceMethod($name)) {
                return $this->$name(...$args);
            }
        } elseif (method_exists($this, $name)) {
            return $this->$name(...$args);
        }

        return call_user_func_array($this->$name, $args);
    }

    /**
     * Get a service object by the given name
     *
     * @param string $name The name of service
     * @return $this
     */
    public function __get($name)
    {
        return $this->$name = $this->wei->get($name, array(), $this->providers);
    }

    /**
     * Receive the base name of current class
     *
     * @return string
     */
    protected static function getServiceName()
    {
        $parts = explode('\\', static::class);
        return lcfirst(end($parts));
    }
}
