<?php

namespace WidgetTest\Validator;

class LessThanTest extends TestCase
{
    /**
     * @dataProvider providerForLessThan
     */
    public function testLessThan($input, $options)
    {
        $this->assertTrue($this->isLessThan($input, $options));
    }

    /**
     * @dataProvider providerForNotLessThan
     */
    public function testNotLessThan($input, $options)
    {
        $this->assertFalse($this->isLessThan($input, $options));
    }

    public function providerForLessThan()
    {
        return array(
            array(7, 8),
            array(0.1, 0.2),
            array('2000-01-01', '2001-01-01'),
            array('10:00', '11:00'),
            array('10:03', '9:24'),
        );
    }

    public function providerForNotLessThan()
    {
        return array(
            array(7, 7),
            array(7, 6),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }
}