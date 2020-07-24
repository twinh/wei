<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class ColorTest extends TestCase
{
    /**
     * @dataProvider providerForColor
     * @param mixed $input
     * @param mixed $options
     */
    public function testColor($input, $options = [])
    {
        $this->assertTrue($this->isColor($input, $options));
    }

    /**
     * @dataProvider providerForNotColor
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotColor($input, $options = [])
    {
        $this->assertFalse($this->isColor($input, $options));
    }

    public function providerForColor()
    {
        return [
            ['#FFFFFF'],
            ['#FFF'],
            ['#ABCDEF'],
            ['#012345'],
            ['#abcdef'],
            ['#AABBCC'],
            ['#fff'],
        ];
    }

    public function providerForNotColor()
    {
        return [
            ['FFF'],
            ['#FGH'],
            ['#ffff'],
            ['#0123456'],
            ['AAFFFF'],
        ];
    }
}
