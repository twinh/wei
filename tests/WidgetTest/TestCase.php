<?php

namespace WidgetTest;

use Widget\Widget;
use PHPUnit_Framework_TestCase;

/**
 * TestCase
 *
 * @package     Widget
 * @author      Twin Huang <twinhuang@qq.com>
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Widget\Base
     */
    protected $object;

    /**
     * The widget container
     *
     * @var \Widget\Widget
     */
    protected $widget;

    /**
     * The widget name of current test case
     *
     * @var string
     */
    protected $widgetName;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->widget = widget();
    }

    /**
     * Get the widget name
     *
     * @return string
     */
    protected function getWidgetName()
    {
        if (empty($this->widgetName)) {
            $names = explode('\\', get_class($this));
            $class = array_pop($names);
            $this->widgetName = substr($class, 0, -4);
        }

        return $this->widgetName;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $widget = $this->widget;
        $name = $this->getWidgetName();

        if ('widget' == strtolower($name)) {
            return;
        }

        if ($widget->has($name)) {
            // Reinstance
            $this->object = $widget->{lcfirst($name)};
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $widget = $this->widget;
        $name = $this->getWidgetName();

        if ('widget' == strtolower($name)) {
            return;
        }

        foreach ($widget->getOption('objects') as $key => $value) {
            if ('widget' == $key) {
                continue;
            }
            $widget->remove($key);
        }

        foreach (get_object_vars($this->widget) as $name => $property) {
            // Preserve the widget container
            if ('widget' == $name) {
                continue;
            }

            // Remove all widget instanced by current test object
            if ($property instanceof \Widget\Base) {
                unset($this->$name);
                $widget->remove($name);
            }
        }

        unset($this->object);

        if (isset($widget->{$name})) {
            unset($widget->{$name});
        }
    }

    /**
     * Invoke the widget by the given name
     *
     * @param string $name The name of widget
     * @param array $args The arguments for widget's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * Get the widget object by the given name
     *
     * @param string $name The name of widget
     * @return \Widget\Base
     */
    public function __get($name)
    {
        return $this->widget->get($name);
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
