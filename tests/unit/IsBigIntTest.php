<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsBigIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForBigInt
     * @param mixed $input
     */
    public function testBigInt($input)
    {
        $this->assertTrue($this->isBigInt($input));
    }

    /**
     * @dataProvider providerForNotBigInt
     * @param mixed $input
     */
    public function testNotBigInt($input)
    {
        $this->assertFalse($this->isBigInt($input));
    }

    public function providerForBigInt()
    {
        return [
            [0],
            [1],
            [-1],
            ['-9223372036854775808'], // -2 ** 63
            ['9223372036854775807'], // 2 ** 63 - 1
        ];
    }

    public function providerForNotBigInt()
    {
        return [
            ['1.0'],
            [-2 ** 63], // become float
            [2 ** 64 - 1], // become float
            ['-9223372036854775809'],
            ['9223372036854775808'],
        ];
    }
}
