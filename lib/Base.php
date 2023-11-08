<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use ReflectionException;

/**
 * The base class for all services
 *
 * @author   Twin Huang <twinhuang@qq.com>
 */
#[\AllowDynamicProperties]
abstract class Base
{
    /**
     * Whether to create a new instance on static call
     *
     * @var bool
     */
    protected static $createNewInstance = false;

    /**
     * The service provider map
     *
     * @var array
     */
    protected $providers = [];

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
    public function __construct(array $options = [])
    {
        if (!isset($options['wei'])) {
            $this->wei = Wei::getContainer();
        } elseif (!$options['wei'] instanceof Wei) {
            throw new \InvalidArgumentException(sprintf(
                'Option "wei" of class "%s" should be an instance of "Wei\Wei"',
                static::class
            ), 1000);
        }
        $this->setOption($options);
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
        return Wei::staticCaller(static::class, $method)(...$args);
    }

    /**
     * Invoke a service by the given name
     *
     * @param string $name The name of service
     * @param array $args The arguments for the service's __invoke method
     * @return mixed
     * @throws ReflectionException
     */
    public function __call(string $name, array $args)
    {
        return $this->wei->caller($this, $name)(...$args);
    }

    /**
     * Get a service object by the given name
     *
     * @param string $name The name of service
     * @return $this
     */
    public function __get($name)
    {
        return $this->{$name} = $this->wei->get($name, [], $this->providers);
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
            return $this->{$method}($value);
        } else {
            $this->{$name} = $value;
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
            return $this->{$method}();
        } else {
            return isset($this->{$name}) ? $this->{$name} : null;
        }
    }

    /**
     * Whether to create a new instance on static call
     *
     * @return bool
     */
    public static function isCreateNewInstance()
    {
        return static::$createNewInstance;
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     */
    public static function instance(): self
    {
        return Wei::getContainer()->getBy(static::class);
    }
}
