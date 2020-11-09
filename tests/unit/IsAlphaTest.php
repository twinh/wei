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

    /**
     * @dataProvider providerForNotAlpha
     * @param mixed $input
     */
    public function testNotAlpha($input)
    {
        $this->assertFalse($this->isAlpha($input));
    }

    public function providerForAlpha()
    {
        return [
            ['abcedfg'],
            ['aBcDeFg'],
        ];
    }

    public function providerForNotAlpha()
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
