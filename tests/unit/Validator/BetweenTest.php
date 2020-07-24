<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class BetweenTest extends TestCase
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

    public function providerForBetween()
    {
        return [
            [20, 10, 30],
            ['2013-01-13', '2013-01-01', '2013-01-31'],
            [1.5, 0.8, 3.2],
        ];
    }

    public function providerForNotBetween()
    {
        return [
            [20, 30, 40],
            ['2013-01-01', '2013-01-13', '2013-01-31'],
        ];
    }
}
