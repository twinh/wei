<?php

namespace Wei;

/**
 * Add the ability to get and call other services for the class
 */
trait ServiceTrait
{
    /**
     * The service provider map
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Invoke a service by the given name
     *
     * @param string $name The name of service
     * @param array $args The arguments for the service's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->{$name}, $args);
    }

    /**
     * Get a service object by the given name
     *
     * @param string $name The name of service
     * @return Base
     */
    public function __get($name)
    {
        return $this->{$name} = $this->wei->get($name, [], $this->providers);
    }
}
