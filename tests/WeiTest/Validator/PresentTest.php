<?php

namespace WeiTest\Validator;

class PresentTest extends TestCase
{
    /**
     * @dataProvider providerForPresent
     */
    public function testPresent($input)
    {
        $this->assertTrue($this->isPresent($input));
    }

    /**
     * @dataProvider providerForNotPresent
     */
    public function testNotPresent($input)
    {
        $this->assertFalse($this->isPresent($input));
    }

    public function providerForPresent()
    {
        return array(
            array('0'),
            array(0),
            array(0.0),
            array('string'),
            array(' '),
            array("\r\n"),
            array("\n"),
            array("\r"),
        );
    }

    public function providerForNotPresent()
    {
        return array(
            array(''),
            array(false),
            array(array()),
            array(null),
        );
    }
}
