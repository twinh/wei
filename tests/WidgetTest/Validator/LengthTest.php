<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class LengthTest extends TestCase
{
    /**
     * @dataProvider providerForLength
     */
    public function testLength($input, $option1, $option2)
    {
        $this->assertTrue($this->isLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForNotLength
     */
    public function testNotLength($input, $option1, $option2)
    {
        $this->assertFalse($this->isLength($input, $option1, $option2));
    }
    
    /**
     * @expectedException \Widget\UnexpectedTypeException
     */
    public function testUnexpectedTypeException()
    {
        $this->isLength(new \stdClass(), 1, 2);
    }

    public function providerForLength()
    {
        $ao = new \ArrayObject(array(
            1, 2,
        ));
        
        return array(
            array('length7', 7, 10),
            array('length7', 0, 10),
            array(array(1, 2), 1, 2),
            array($ao, 1, 10),
        );
    }

    public function providerForNotLength()
    {
        $ao = new \ArrayObject(array(
            1, 2,
        ));
        
        return array(
            array('length7', 0, 0),
            array('length7', -2, -1),
            array(array(1, 2), 10, 0),
            array($ao, 0, 1),
        );
    }
}