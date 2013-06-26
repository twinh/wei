<?php

namespace WidgetTest\Validator;

class DivisibleByTest extends TestCase
{
    /**
     * @dataProvider providerForDivisibleBy
     */
    public function testDivisibleBy($input, $divisor)
    {
        $this->assertTrue($this->isDivisibleBy($input, $divisor));
    }

    /**
     * @dataProvider providerForNotDivisibleBy
     */
    public function testNotDivisibleBy($input, $divisor)
    {
        $this->assertFalse($this->isDivisibleBy($input, $divisor));
    }

    public function providerForDivisibleBy()
    {
        return array(
            array('10', '5'),
            array('2', '1'),
            array(2.5, 0.5),
        );
    }

    public function providerForNotDivisibleBy()
    {
        return array(
            array(10, 3),
            array(11, 2),
            array(0.7, 0.2),
            array(5, 0.1) // Returns 0.1
        );
    }
}
