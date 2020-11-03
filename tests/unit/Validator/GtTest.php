<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class GtTest extends TestCase
{
    /**
     * @dataProvider providerForGt
     * @param mixed $input
     * @param mixed $options
     */
    public function testGt($input, $options)
    {
        $this->assertTrue($this->isGt($input, $options));
    }

    /**
     * @dataProvider providerForNotGt
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotGt($input, $options)
    {
        $this->assertFalse($this->isGt($input, $options));
    }

    public function providerForGt()
    {
        return [
            [7, 6],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }

    public function providerForNotGt()
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
