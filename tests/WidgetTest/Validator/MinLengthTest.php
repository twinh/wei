<?php

namespace WidgetTest\Validator;

class MinLengthTest extends TestCase
{
    /**
     * @dataProvider providerForMinLength
     */
    public function testMinLength($options)
    {
        $this->assertTrue($this->isMinLength('length7', $options));
    }

    /**
     * @dataProvider providerForNotMinLength
     */
    public function testNotMinLength($options)
    {
        $this->assertFalse($this->isMinLength('length7', $options));
    }
    
    public function providerForMinLength()
    {
        return array(
            array(6),
            array(1),
            array(-1)
        );
    }

    public function providerForNotMinLength()
    {
        return array(
            array(8),
            array(200)
        );
    }
}