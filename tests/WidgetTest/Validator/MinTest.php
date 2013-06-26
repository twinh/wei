<?php

namespace WidgetTest\Validator;

class MinTest extends TestCase
{
    /**
     * @dataProvider providerForMin
     */
    public function testMin($input, $options)
    {
        $this->assertTrue($this->isMin($input, $options));
    }

    /**
     * @dataProvider providerForNotMin
     */
    public function testNotMin($input, $options)
    {
        $this->assertFalse($this->isMin($input, $options));
    }
    
    public function providerForMin()
    {
        return array(
            array(7, 7),
            array(7, 6),
            array(0.1, 0.01),
            array('2000-01-01', '1999-01-01'),
            array('10:03', '09:24'),
        );
    }

    public function providerForNotMin()
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