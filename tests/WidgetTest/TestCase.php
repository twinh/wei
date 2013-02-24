<?php

namespace WidgetTest;

use Widget\Widget;
use Widget\WidgetAwareInterface;
use PHPUnit_Framework_TestCase;

/**
 * TestCase
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class TestCase extends PHPUnit_Framework_TestCase implements WidgetAwareInterface
{
    /**
     * @var \Widget\WidgetProvider
     */
    protected $object;

    /**
     * The widget manager
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

        $this->widget = Widget::create();
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
     *
     * @todo How to inject widget manager instead of Widget::create()
     */
    protected function setUp()
    {
        $widget = $this->widget;
        $name = $this->getWidgetName();
        
        if ($widget->has($name)) {
            // Removed
            $widget->remove($name);
            if (isset($widget->{$name})) {
                unset($widget->{$name});
            }
            
            // Reinstance
            $this->object = $widget->{$name};
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

        foreach (get_object_vars($this) as $name => $property) {
            // Preserve the widget manager
            if ('widget' == $name) {
                continue;
            }

            // Remove all widget instanlled by current test object
            if ($property instanceof \Widget\WidgetProvider) {
                unset($this->$name);
                $widget->remove($name);
            }
        }
        
        unset($this->object);

        // Remove $this->object
        $widget->remove($name);
        
        if (isset($widget->{$name})) {
            unset($widget->{$name});
        } 
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->widget->get($name);
    }
}
