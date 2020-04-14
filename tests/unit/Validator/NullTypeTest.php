<?php

namespace WeiTest\Validator;

class NullTypeTest extends TestCase
{
    /**
     * @dataProvider providerForNull
     */
    public function testNull($input)
    {
        $this->assertTrue($this->isNullType($input));
    }

    /**
     * @dataProvider providerForNotNull
     */
    public function testNotNull($input)
    {
        $this->assertFalse($this->isNullType($input));
    }

    public function providerForNull()
    {
        return array(
            array(null),
        );
    }

    public function providerForNotNull()
    {
        return array(
            array(''),
            array(false),
            array(0),
            array(0.0),
            array(array()),
            array('0')
        );
    }
}
