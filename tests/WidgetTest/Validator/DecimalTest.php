<?php

namespace WidgetTest\Validator;

class DecimalTest extends TestCase
{
    /**
     * @dataProvider providerForDecimal
     */
    public function testDecimal($input)
    {
        $this->assertTrue($this->isDecimal($input));
    }

    /**
     * @dataProvider providerForNotDecimal
     */
    public function testNotDecimal($input)
    {
        $this->assertFalse($this->isDecimal($input));
    }

    public function providerForDecimal()
    {
        return array(
            array('0.123'),
            array('1.0'),
            array(1.0),
            array('6.01'),
            array('-2.234'),
            array(+2.2),
            array(-2.2),
            array(3E-3),
            array(2.3E-3),
            array(2.3E3),
        );
    }

    public function providerForNotDecimal()
    {
        return array(
            array('1 23456'),
            array('a bcdefg'),
            array('0.0.1'),
            array('string'),
        );
    }
}
