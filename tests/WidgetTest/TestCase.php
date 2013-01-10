<?php

namespace WidgetTest;

use Widget\Widget;
use Widget\Widgetable;

/**
 * TestCase
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class TestCase extends \PHPUnit_Framework_TestCase implements Widgetable
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
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
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
        $this->object = $this->widget->{$this->getWidgetName()};
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Widget::create()->remove($this->getWidgetName());
    }

    public function __call($name, $args)
    {
        return $this->widget->invoke($name, $args);
    }

    public function __get($name)
    {
        return $this->widget->get($name);
    }
}