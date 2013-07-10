<?php

namespace WidgetTest;

class MapTest extends TestCase
{
    public function setUp()
    {
        $this->widget->config('map', array(
            'file' => __DIR__ . '/Fixtures/map.php',
        ));

        parent::setUp();
    }

    public function testGet()
    {
        $this->assertEquals('Yes', $this->map('yesOrNo', '1'));
    }
}