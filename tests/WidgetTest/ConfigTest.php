<?php

namespace WidgetTest;

class ConfigTest extends TestCase
{
    /**
     * @var \Widget\Config
     */
    protected $object;

    public function testConfig()
    {
        $this->config('key', 'value');

        $this->assertEquals('value', $this->config('key'));

        $this->config('first/second', 'value2');

        $this->assertEquals(array('second' => 'value2',), $this->config('first'));
    }
}