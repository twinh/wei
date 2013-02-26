<?php

namespace WidgetTest;

class WidgetTest extends TestCase
{
    public function testConfig()
    {
        $widget = $this->widget;
        
        $widget->config('key', 'value');

        $this->assertEquals('value', $widget->config('key'));

        $widget->config('first/second', 'value2');

        $this->assertEquals(array('second' => 'value2',), $widget->config('first'));
    }
}
