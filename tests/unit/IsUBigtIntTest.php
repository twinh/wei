<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUBigtIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUBigInt
     * @param mixed $input
     */
    public function testUBigInt($input)
    {
        $this->assertTrue($this->isUBigInt($input));
    }

    /**
     * @dataProvider providerForNotUBigInt
     * @param mixed $input
     */
    public function testNotUBigInt($input)
    {
        $this->assertFalse($this->isUBigInt($input));
    }

    public function providerForUBigInt()
    {
        return [
            [1],
            [0],
            [2 ^ 64 - 1],
        ];
    }

    public function providerForNotUBigInt()
    {
        return [
            ['1.0'],
            [0 - 1],
            [(2 ^ 64 - 1) + 1],
        ];
    }
}
