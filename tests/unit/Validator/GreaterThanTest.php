<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class GreaterThanTest extends TestCase
{
    /**
     * @dataProvider providerForGreaterThan
     * @param mixed $input
     * @param mixed $options
     */
    public function testGreaterThan($input, $options)
    {
        $this->assertTrue($this->isGreaterThan($input, $options));
    }

    /**
     * @dataProvider providerForNotGreaterThan
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotGreaterThan($input, $options)
    {
        $this->assertFalse($this->isGreaterThan($input, $options));
    }

    public function providerForGreaterThan()
    {
        return [
            [7, 6],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }

    public function providerForNotGreaterThan()
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
}
