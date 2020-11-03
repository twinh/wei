<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class LteTest extends TestCase
{
    /**
     * @dataProvider providerForLte
     * @param mixed $input
     * @param mixed $options
     */
    public function testLte($input, $options)
    {
        $this->assertTrue($this->isLte($input, $options));
    }

    /**
     * @dataProvider providerForNotLte
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotLte($input, $options)
    {
        $this->assertFalse($this->isLte($input, $options));
    }

    public function providerForLte()
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

    public function providerForNotLte()
    {
        return [
            [7, 6],
            [0.1, 0.01],
            ['2000-01-01', '1999-01-01'],
            ['10:03', '09:24'],
        ];
    }
}
