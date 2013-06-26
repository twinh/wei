<?php

namespace WidgetTest\Validator;

class EqualsTest extends TestCase
{
    /**
     * @dataProvider providerForEquals
     */
    public function testEquals($input, $equals, $strict = null)
    {
        $this->assertTrue($this->isEquals($input, $equals, $strict));
    }

    /**
     * @dataProvider providerForNotEquals
     */
    public function testNotEquals($input, $equals, $strict = null)
    {
        $this->assertFalse($this->isEquals($input, $equals, $strict));
    }

    public function providerForEquals()
    {
        return array(
            array('abc', 'abc'),
            array(0, null),
            array(null, null, true),
            array(new \stdClass, new \stdClass)
        );
    }

    public function providerForNotEquals()
    {
        return array(
            array('abc', 'bbc'),
            array(0, null, true),
            array(new \stdClass(), new \stdClass(), true)
        );
    }
}
