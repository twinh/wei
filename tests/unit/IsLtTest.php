<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsLtTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLt
     * @param mixed $input
     * @param mixed $options
     */
    public function testLt($input, $options)
    {
        $this->assertTrue($this->isLt($input, $options));
    }

    /**
     * @dataProvider providerForNotLt
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotLt($input, $options)
    {
        $this->assertFalse($this->isLt($input, $options));
    }

    public static function providerForLt()
    {
        return [
            [7, 8],
            [0.1, 0.2],
            ['2000-01-01', '2001-01-01'],
            ['10:00', '11:00'],
            ['10:03', '9:24'],
        ];
    }

    public static function providerForNotLt()
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
