<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class GteTest extends TestCase
{
    /**
     * @dataProvider providerForGte
     * @param mixed $input
     * @param mixed $options
     */
    public function testGte($input, $options)
    {
        $this->assertTrue($this->isGte($input, $options));
    }

    /**
     * @dataProvider providerForNotGte
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotGte($input, $options)
    {
        $this->assertFalse($this->isGte($input, $options));
    }

    public function providerForGte()
    {
        return [
            [7, 6],
            [7, 7],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }

    public function providerForNotGte()
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
