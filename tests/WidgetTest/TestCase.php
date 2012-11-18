<?php

namespace WidgetTest;

use Widget\Widget;

/**
 * TestCase
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Widget\WidgetProvider
     */
    protected $object;

    /**
     * The widget name of current test case
     * 
     * @var string
     */
    protected $widgetName;
    
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
        $this->object = Widget::create()->{$this->getWidgetName()};
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Widget::create()->remove($this->getWidgetName());
    }
}