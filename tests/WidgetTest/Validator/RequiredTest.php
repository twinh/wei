<?php

namespace WidgetTest\Validator;

class RequiredTest extends TestCase
{
    /**
     * @dataProvider providerForRequired
     */
    public function testRequired($input)
    {
        $this->assertTrue($this->isRequired($input));
    }

    /**
     * @dataProvider providerForNotRequired
     */
    public function testNotRequired($input)
    {
        $this->assertFalse($this->isRequired($input));
    }

    public function providerForRequired()
    {
        return array(
            array('123'),
            array('false'),
            array('off'),
            array('on'),
            array('true'),
            array(0),
            array('0'),
            array(0.0),
            array(true),
            array('string'),
            array(' '),
            array("\r\n"),
        );
    }

    public function providerForNotRequired()
    {
        return array(
            array(array()),
            array(false),
            array(''),
            array(null),
        );
    }
}
