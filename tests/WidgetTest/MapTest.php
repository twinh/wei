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

    public function testToJson()
    {
        $this->assertEquals('{"1":"Yes","0":"No"}', $this->map->toJson('yesOrNo'));
    }

    public function testToOptions()
    {
        $this->assertEquals('<option value="1">Yes</option><option value="0">No</option>', $this->map->toOptions('yesOrNo'));

        $this->assertEquals('<optgroup label="province1"><option value="city1">shenzhen</option></optgroup><optgroup label="province2"><option value="city2">shanghai</option></optgroup>', $this->map->toOptions('country'));
    }

    public function testToSimpleArray()
    {
        $this->assertEquals(array(
            'city1' => 'shenzhen',
            'city2' => 'shanghai'
        ), $this->map->toSimpleArray('country'));
    }

    public function testMapFileNotFoundException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Map file "notFound.php" not found');

        new \Widget\Map(array(
            'widget' => $this->widget,
            'file' => 'notFound.php'
        ));
    }

    public function testGetInverse()
    {
        $this->assertEquals(
            array(
                'Yes' => '1',
                'No' => '0',
            ),
            $this->map->getInverse('yesOrNo')
        );

        $this->assertEquals('1', $this->map->getInverse('yesOrNo', 'Yes'));
        $this->assertEquals('0', $this->map->getInverse('yesOrNo', 'No'));
    }

    public function getNotDefinedMap()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Map name "notFound" not defined');

        $this->map('notDefined');
    }
}