<?php

namespace WidgetTest\Validator;

class GreaterThanTest extends TestCase
{
    /**
     * @dataProvider providerForGreaterThan
     */
    public function testGreaterThan($input, $options)
    {
        $this->assertTrue($this->isGreaterThan($input, $options));
    }

    /**
     * @dataProvider providerForNotGreaterThan
     */
    public function testNotGreaterThan($input, $options)
    {
        $this->assertFalse($this->isGreaterThan($input, $options));
    }

    public function providerForGreaterThan()
    {
        return array(
            array(7, 6),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }

    public function providerForNotGreaterThan()
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
}