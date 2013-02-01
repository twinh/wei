<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class RangeTest extends TestCase
{
    /**
     * @dataProvider providerForRange
     */
    public function testRange($input, $min, $max)
    {
        $this->assertTrue($this->isRange($input, $min, $max));
    }

    /**
     * @dataProvider providerForNotRange
     */
    public function testNotRange($input, $min, $max)
    {
        $this->assertFalse($this->isRange($input, $min, $max));
    }

    public function providerForRange()
    {
        return array(
            array(20, 10, 30),
            array('2013-01-13', '2013-01-01', '2013-01-31'),
            array(1.5, 0.8, 3.2)
        );
    }

    public function providerForNotRange()
    {
        return array(
            array(20, 30, 40),
            array('2013-01-01', '2013-01-13', '2013-01-31'),
        );
    }
}
