<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsAlphaTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForAlpha
     * @param mixed $input
     */
    public function testAlpha($input)
    {
        $this->assertTrue($this->isAlpha($input));
    }

    public static function providerForAlpha()
    {
        return [
            ['abcedfg'],
            ['aBcDeFg'],
        ];
    }

    /**
     * @dataProvider providerForNotAlpha
     * @param mixed $input
     */
    public function testNotAlpha($input)
    {
        $this->assertFalse($this->isAlpha($input));
    }

    public static function providerForNotAlpha()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['abcdefg1'],
            ['a bcdefg'],
            ['123'],
        ];
    }
}
