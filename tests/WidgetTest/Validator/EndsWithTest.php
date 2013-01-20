<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class EndsWithTest extends TestCase
{
    /**
     * @dataProvider providerForEndsWith
     */
    public function testEndsWith($input, $options)
    {
        $this->assertTrue($this->isEndsWith($input, $options));
    }

    /**
     * @dataProvider providerForNotEndsWith
     */
    public function testNotEndsWith($input, $options)
    {
        $this->assertFalse($this->isEndsWith($input, $options));
    }

    public function providerForEndsWith()
    {
        return array(
            array('abc', array(
                'findMe' => 'c'
            )),
            array('ABC', array(
                'findMe' => 'c'
            )),
        );
    }

    public function providerForNotEndsWith()
    {
        return array(
            array('abc', array(
                'findMe' => ''
            )),
            array('abc', array(
                'findMe' => 'b'
            )),
            array('ABC', array(
                'findMe' => 'c',
                'case' => true
            )),
        );
    }
}
