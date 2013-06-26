<?php

namespace WidgetTest\Validator;

class MaxTest extends TestCase
{
    /**
     * @dataProvider providerForMax
     */
    public function testMax($input, $options)
    {
        $this->assertTrue($this->isMax($input, $options));
    }

    /**
     * @dataProvider providerForNotMax
     */
    public function testNotMax($input, $options)
    {
        $this->assertFalse($this->isMax($input, $options));
    }
    
    public function providerForMax()
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

    public function providerForNotMax()
    {
        return array(
            array(7, 6),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }
}