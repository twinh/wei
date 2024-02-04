<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsBetweenTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForBetween
     * @param mixed $input
     * @param mixed $min
     * @param mixed $max
     */
    public function testBetween($input, $min, $max)
    {
        $this->assertTrue($this->isBetween($input, $min, $max));
    }

    /**
     * @dataProvider providerForNotBetween
     * @param mixed $input
     * @param mixed $min
     * @param mixed $max
     */
    public function testNotBetween($input, $min, $max)
    {
        $this->assertFalse($this->isBetween($input, $min, $max));
    }

    public static function providerForBetween()
    {
        return [
            [20, 10, 30],
            ['2013-01-13', '2013-01-01', '2013-01-31'],
            [1.5, 0.8, 3.2],
            [20, 0, 30],
            [-1, -2, 0],
        ];
    }

    public static function providerForNotBetween()
    {
        return [
            [20, 30, 40],
            ['2013-01-01', '2013-01-13', '2013-01-31'],
            [-1, 0, 30],
            [-3, -2, 0],
        ];
    }
}
