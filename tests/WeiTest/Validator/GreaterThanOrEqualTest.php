<?php

namespace WeiTest\Validator;

class GreaterThanOrEqualTest extends TestCase
{
    /**
     * @dataProvider providerForGreaterThanOrEqual
     */
    public function testGreaterThanOrEqual($input, $options)
    {
        $this->assertTrue($this->isGreaterThanOrEqual($input, $options));
    }

    /**
     * @dataProvider providerForNotGreaterThanOrEqual
     */
    public function testNotGreaterThanOrEqual($input, $options)
    {
        $this->assertFalse($this->isGreaterThanOrEqual($input, $options));
    }

    public function providerForGreaterThanOrEqual()
    {
        return array(
            array(7, 6),
            array(7, 7),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }

    public function providerForNotGreaterThanOrEqual()
    {
        return array(
            array(7, 8),
            array(0.1, 0.2),
            array('2000-01-01', '2001-01-01'),
            array('10:00', '11:00'),
            array('10:03', '9:24'),
        );
    }
}
