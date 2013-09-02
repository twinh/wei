<?php

namespace WidgetTest\Validator;

class EqualToTest extends TestCase
{
    /**
     * @dataProvider providerForEquals
     */
    public function testEquals($input, $equals)
    {
        $this->assertTrue($this->isEqualTo($input, $equals));
    }

    /**
     * @dataProvider providerForNotEquals
     */
    public function testNotEquals($input, $equals)
    {
        $this->assertFalse($this->isEqualTo($input, $equals));
    }

    public function providerForEquals()
    {
        return array(
            array('abc', 'abc'),
            array(0, null),
            array(0, ''),
            array(null, null),
            array(new \stdClass, new \stdClass)
        );
    }

    public function providerForNotEquals()
    {
        return array(
            array('abc', 'bbc'),
            array('', array()),
            array(new \stdClass, new \ArrayObject())
        );
    }
}
