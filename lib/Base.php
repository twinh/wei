<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The base class for all services
 *
 * @author   Twin Huang <twinhuang@qq.com>
 */
abstract class Base
{
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
     * Invoke a service by the given name
     *
     * @param string $name The name of service
     * @param array $args The arguments for the service's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * Get a service object by the given name
     *
     * @param  string $name The name of service
     * @return $this
     */
    public function __get($name)
    {
        return $this->$name = $this->wei->get($name, array(), $this->providers);
    }
}
