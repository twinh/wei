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

    public function testGetMapData()
    {
        $this->assertEquals(
            array(
                '1' => 'Yes',
                '0' => 'No',
            ),
            $this->map('yesOrNo')
        );
    }

    public function testtoJson()
    {
        $this->assertEquals('{"1":"Yes","0":"No"}', $this->map->toJson('yesOrNo'));
    }

    public function testToOptions()
    {
        $this->assertEquals('<option value="1">Yes</option><option value="0">No</option>', $this->map->toOptions('yesOrNo'));
    }

    public function testMapFileNotFoundException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Map file "notFound.php" not found');

        new \Widget\Map(array(
            'widget' => $this->widget,
            'file' => 'notFound.php'
        ));
    }
}