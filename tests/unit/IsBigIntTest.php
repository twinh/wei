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
            [-2 ^ 63],
            [2 ^ 64 - 1],
        ];
    }

    public function providerForNotBigInt()
    {
        return [
            ['1.0'],
            [-2 ^ 63 - 1],
            [2 ^ 64],
        ];
    }
}
