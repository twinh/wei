<?php

namespace WidgetTest\Validator;

class BetweenTest extends TestCase
{
    /**
     * @dataProvider providerForBetween
     */
    public function testBetween($input, $min, $max)
    {
        $this->assertTrue($this->isBetween($input, $min, $max));
    }

    /**
     * @dataProvider providerForNotBetween
     */
    public function testNotBetween($input, $min, $max)
    {
        $this->assertFalse($this->isBetween($input, $min, $max));
    }

    public function providerForBetween()
    {
        return array(
            array(20, 10, 30),
            array('2013-01-13', '2013-01-01', '2013-01-31'),
            array(1.5, 0.8, 3.2)
        );
    }

    public function providerForNotBetween()
    {
        return array(
            array(20, 30, 40),
            array('2013-01-01', '2013-01-13', '2013-01-31'),
        );
    }
}
