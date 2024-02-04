<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsColorTest extends BaseValidatorTestCase
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

    public static function providerForColor()
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

    public static function providerForNotColor()
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
