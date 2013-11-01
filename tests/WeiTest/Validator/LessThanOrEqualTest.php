<?php

namespace WeiTest\Validator;

class LessThanOrEqualTest extends TestCase
{
    /**
     * @dataProvider providerForLessThanOrEqual
     */
    public function testLessThanOrEqual($input, $options)
    {
        $this->assertTrue($this->isLessThanOrEqual($input, $options));
    }

    /**
     * @dataProvider providerForNotLessThanOrEqual
     */
    public function testNotLessThanOrEqual($input, $options)
    {
        $this->assertFalse($this->isLessThanOrEqual($input, $options));
    }

    public function providerForLessThanOrEqual()
    {
        return array(
            array(7, 7),
            array(7, 8),
            array(0.1, 0.2),
            array('2000-01-01', '2001-01-01'),
            array('10:00', '11:00'),
            array('10:03', '9:24'),
        );
    }

    public function providerForNotLessThanOrEqual()
    {
        return array(
            array(7, 6),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }
}
