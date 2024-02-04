<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsLessThanOrEqualTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLessThanOrEqual
     * @param mixed $input
     * @param mixed $options
     */
    public function testLessThanOrEqual($input, $options)
    {
        $this->assertTrue($this->isLessThanOrEqual($input, $options));
    }

    /**
     * @dataProvider providerForNotLessThanOrEqual
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotLessThanOrEqual($input, $options)
    {
        $this->assertFalse($this->isLessThanOrEqual($input, $options));
    }

    public static function providerForLessThanOrEqual()
    {
        return [
            [7, 7],
            [7, 8],
            [0.1, 0.2],
            ['2000-01-01', '2001-01-01'],
            ['10:00', '11:00'],
            ['10:03', '9:24'],
        ];
    }

    public static function providerForNotLessThanOrEqual()
    {
        return [
            [7, 6],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }
}
