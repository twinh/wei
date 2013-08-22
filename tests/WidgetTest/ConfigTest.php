<?php

namespace WidgetTest;

class ConfigTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->widget->setConfig(array(
            'yesOrNo' => array(
                '1' => 'Yes',
                '0' => 'No'
            ),
            'priorities' => array(
                '5' => 'Low',
                '10' => 'Normal',
                '15' => 'High'
            ),
            'country' => array(
                'province1' => array(
                    'city1' => 'shenzhen'
                ),
                'province2' => array(
                    'city2' => 'shanghai'
                )
            )
        ));
    }

    public function testToJson()
    {
        $this->assertEquals('{"1":"Yes","0":"No"}', $this->config->toJson('yesOrNo'));
    }

    public function testToOptions()
    {
        $this->assertEquals('<option value="1">Yes</option><option value="0">No</option>', $this->config->toOptions('yesOrNo'));

        $this->assertEquals('<optgroup label="province1"><option value="city1">shenzhen</option></optgroup><optgroup label="province2"><option value="city2">shanghai</option></optgroup>', $this->config->toOptions('country'));
    }
}