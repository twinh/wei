<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class LengthTest extends TestCase
{
    /**
     * @dataProvider providerForLength
     */
    public function testLength($options)
    {
        $this->assertTrue($this->isLength('length7', $options));
    }

    /**
     * @dataProvider providerForNotLength
     */
    public function testNotLength($options)
    {
        $this->assertFalse($this->isLength('length7', $options));
    }
    

    public function providerForLength()
    {
        return array(
            array(7),
            array(array('min' => 7, 'max' => 10)),
            array(array('min' => 0, 'max' => 10)),
            array(array('min' => 5)),
        );
    }

    public function providerForNotLength()
    {
        return array(
            array(5),
            array(array('max' => 5)),
            array(array('min' => 0, 'max' => 0)),
            array(array('min' => -2, 'max' => -1)),
            array(array('min' => 10, 'max' => 0)),
        );
    }
}