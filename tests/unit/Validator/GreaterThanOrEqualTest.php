<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class GreaterThanOrEqualTest extends TestCase
{
    /**
     * @dataProvider providerForGreaterThanOrEqual
     * @param mixed $input
     * @param mixed $options
     */
    public function testGreaterThanOrEqual($input, $options)
    {
        $this->assertTrue($this->isGreaterThanOrEqual($input, $options));
    }

    /**
     * @dataProvider providerForNotGreaterThanOrEqual
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotGreaterThanOrEqual($input, $options)
    {
        $this->assertFalse($this->isGreaterThanOrEqual($input, $options));
    }

    public function providerForGreaterThanOrEqual()
    {
        return [
            [7, 6],
            [7, 7],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }

    public function providerForNotGreaterThanOrEqual()
    {
        return [
            [7, 8],
            [0.1, 0.2],
            ['2000-01-01', '2001-01-01'],
            ['10:00', '11:00'],
            ['10:03', '9:24'],
        ];
    }
}
