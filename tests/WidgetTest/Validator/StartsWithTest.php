<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class StartsWithTest extends TestCase
{
    /**
     * @dataProvider providerForStartsWith
     */
    public function testStartsWith($input, $options)
    {
        $this->assertTrue($this->isStartsWith($input, $options));
    }

    /**
     * @dataProvider providerForNotStartsWith
     */
    public function testNotStartsWith($input, $options)
    {
        $this->assertFalse($this->isStartsWith($input, $options));
    }

    public function providerForStartsWith()
    {
        return array(
            array('abc', array(
                'findMe' => 'a'
            )),
            array('ABC', array(
                'findMe' => 'A'
            )),
        );
    }

    public function providerForNotStartsWith()
    {
        return array(
            array('abc', array(
                'findMe' => ''
            )),
            array('abc', array(
                'findMe' => 'b'
            )),
            array('ABC', array(
                'findMe' => 'a',
                'case' => true
            )),
        );
    }
}
