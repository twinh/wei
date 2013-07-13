<?php

namespace WidgetTest\Validator;

class DigitTest extends TestCase
{
    /**
     * @dataProvider providerForDigit
     */
    public function testDigit($input)
    {
        $this->assertTrue($this->isDigit($input));
    }

    /**
     * @dataProvider providerForNotDigit
     */
    public function testNotDigit($input)
    {
        $this->assertFalse($this->isDigit($input));
    }

    public function providerForDigit()
    {
        return array(
            array('0'),
            array(0),
            array(0.0),
            array('123456'),
            array('0123456'),
        );
    }

    public function providerForNotDigit()
    {
        return array(
            array('0.123'),
            array('a bcdefg'),
            array('1 23456'),
            array('string'),
        );
    }
}
