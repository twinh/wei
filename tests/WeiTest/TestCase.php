<?php

namespace WeiTest;

use Wei\Wei;
use PHPUnit_Framework_TestCase;

/**
 * TestCase
 *
 * @package     Wei
 * @author      Twin Huang <twinhuang@qq.com>
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Wei\Base
     */
    protected $object;

    /**
     * The wei container
     *
     * @var \Wei\Wei
     */
    protected $wei;

    /**
     * The wei name of current test case
     *
     * @var string
     */
    protected $weiName;

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
    protected function getWeiName()
    {
        if (empty($this->weiName)) {
            $names = explode('\\', get_class($this));
            $class = array_pop($names);
            $this->weiName = substr($class, 0, -4);
        }

        return $this->weiName;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $wei = $this->wei;
        $name = $this->getWeiName();

        if ('wei' == strtolower($name)) {
            return;
        }

        if ($wei->has($name)) {
            // Reinstance
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
        $name = $this->getWeiName();

        if ('wei' == strtolower($name)) {
            return;
        }

        foreach ($wei->getOption('objects') as $key => $value) {
            if ('wei' == $key) {
                continue;
            }
            $wei->remove($key);
        }

        foreach (get_object_vars($this->wei) as $name => $property) {
            // Preserve the wei container
            if ('wei' == $name) {
                continue;
            }

            // Remove all wei instanced by current test object
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
     * Invoke the wei by the given name
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
     * Get the wei object by the given name
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
