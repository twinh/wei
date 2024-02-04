<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsLessThanTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLessThan
     * @param mixed $input
     * @param mixed $options
     */
    public function testLessThan($input, $options)
    {
        $this->assertTrue($this->isLessThan($input, $options));
    }

    /**
     * @dataProvider providerForNotLessThan
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotLessThan($input, $options)
    {
        $this->assertFalse($this->isLessThan($input, $options));
    }

    public static function providerForLessThan()
    {
        return [
            [7, 8],
            [0.1, 0.2],
            ['2000-01-01', '2001-01-01'],
            ['10:00', '11:00'],
            ['10:03', '9:24'],
        ];
    }

    public static function providerForNotLessThan()
    {
        return [
            [7, 7],
            [7, 6],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }
}
