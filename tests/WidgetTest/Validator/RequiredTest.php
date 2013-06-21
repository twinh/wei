<?php

namespace WidgetTest\Validator;

class RequiredTest extends TestCase
{
    /**
     * @dataProvider providerForRequired
     */
    public function testRequired($input)
    {
        $this->assertTrue($this->is('Required', $input));
        
        $this->assertFalse($this->is('notRequired', $input));
    }

    /**
     * @dataProvider providerForNotRequired
     */
    public function testNotRequired($input)
    {
        $this->assertFalse($this->is('Required', $input));
        
        $this->assertTrue($this->is('notRequired', $input));
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
