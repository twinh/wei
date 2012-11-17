<?php

namespace WidgetTest;

class ConfigTest extends TestCase
{
    /**
     * @var \Widget\Config
     */
    protected $object;

    /**
     * @covers \Widget\Config::__invoke
     */
    public function test__invoke()
    {
        $widget = $this->object;

        $widget->config('key', 'value');

        $this->assertEquals('value', $widget->config('key'));

        $widget->config('first/second', 'value2');

        $this->assertEquals(array('second' => 'value2',), $widget->config('first'));
    }

}