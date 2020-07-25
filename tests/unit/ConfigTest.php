<?php

namespace WeiTest;

/**
 * @internal
 */
final class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->wei->setConfig([
            'yesOrNo' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'priorities' => [
                '5' => 'Low',
                '10' => 'Normal',
                '15' => 'High',
            ],
            'country' => [
                'province1' => [
                    'city1' => 'shenzhen',
                ],
                'province2' => [
                    'city2' => 'shanghai',
                ],
            ],
        ]);
    }

    public function testToJson()
    {
        $this->assertEquals('{"1":"Yes","0":"No"}', $this->config->toJson('yesOrNo'));
    }

    public function testToOptions()
    {
        $this->assertEquals(implode('', [
            '<option value="1">Yes</option>',
            '<option value="0">No</option>',
        ]), $this->config->toOptions('yesOrNo'));

        $this->assertEquals(implode('', [
            '<optgroup label="province1">',
            '<option value="city1">shenzhen</option>',
            '</optgroup>',
            '<optgroup label="province2">',
            '<option value="city2">shanghai</option>',
            '</optgroup>',
        ]), $this->config->toOptions('country'));
    }
}
