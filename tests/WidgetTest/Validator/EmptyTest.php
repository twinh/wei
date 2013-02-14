<?php

namespace WidgetTest\Validator;

class EmptyTest extends TestCase
{
    protected $name = 'emptyValue';
    
    /**
     * @dataProvider providerForEmpty
     */
    public function testEmpty($input)
    {
        $empty = $this->isEmpty;
        $this->assertTrue($empty($input));
    }

    /**
     * @dataProvider providerForNotEmpty
     */
    public function testNotEmpty($input)
    {
        $empty = $this->isEmpty;
        $this->assertFalse($empty($input));
    }

    public function providerForEmpty()
    {
        return array(
            array(''),
            array(false),
            array(0),
            array(0.0),
            array(array()),
            array('0'),
            array(null),
        );
    }

    public function providerForNotEmpty()
    {
        return array(
            array('string'),
            array(' '),
            array("\r\n"),
        );
    }
}