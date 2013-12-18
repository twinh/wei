<?php

namespace WeiTest;

use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Wei\Base
     */
    protected $object;

    /**
     * The service container
     *
     * @var \Wei\Wei
     */
    protected $wei;

    /**
     * The service name of current test case
     *
     * @var string
     */
    protected $serviceName;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->wei = wei();
    }

    /**
     * Get the wei name
     *
     * @return string
     */
    protected function getServiceName()
    {
        if (empty($this->serviceName)) {
            $names = explode('\\', get_class($this));
            $class = array_pop($names);
            $this->serviceName = substr($class, 0, -4);
        }
        return $this->serviceName;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $wei = $this->wei;
        $name = $this->getServiceName();

        if ('wei' == strtolower($name)) {
            return;
        }

        if ($wei->has($name)) {
            // Re-instance
            $this->object = $wei->{lcfirst($name)};
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $wei = $this->wei;
        $name = $this->getServiceName();

        if ('wei' == strtolower($name)) {
            return;
        }

        foreach ($wei->getServices() as $key => $value) {
            if ('wei' == $key) {
                continue;
            }
            $wei->remove($key);
        }

        foreach (get_object_vars($this->wei) as $name => $property) {
            // Preserve the service container
            if ('wei' == $name) {
                continue;
            }

            // Remove all service instanced by current test object
            if ($property instanceof \Wei\Base) {
                unset($this->$name);
                $wei->remove($name);
            }
        }

        unset($this->object);

        if (isset($wei->{$name})) {
            unset($wei->{$name});
        }
    }

    /**
     * Invoke the service by the given name
     *
     * @param string $name The name of wei
     * @param array $args The arguments for wei's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * Get the service object by the given name
     *
     * @param string $name The name of wei
     * @return \Wei\Base
     */
    public function __get($name)
    {
        return $this->wei->get($name);
    }

    /**
     * Asserts that an array is contains another array
     *
     * @param array $subset
     * @param array $parent
     * @param string $message
     * @return void
     */
    protected function assertIsSubset($subset, $parent, $message = '')
    {
        if (!(is_array($parent) && $subset)) {
            $this->assertTrue(false, $message);
            return;
        }

        foreach ($subset as $item) {
            if (!in_array($item, $parent)) {
                $this->assertTrue(false, $message);
            }
        }

        $this->assertTrue(true);
    }
}
