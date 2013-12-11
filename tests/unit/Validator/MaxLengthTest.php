<?php

namespace WeiTest\Validator;

class MaxLengthTest extends TestCase
{
    /**
     * @dataProvider providerForMaxLength
     */
    public function testMaxLength($options)
    {
        $this->assertTrue($this->isMaxLength('length7', $options));
    }

    /**
     * @dataProvider providerForNotMaxLength
     */
    public function testNotMaxLength($options)
    {
        $this->assertFalse($this->isMaxLength('length7', $options));
    }
    
    public function providerForMaxLength()
    {
        return array(
            array(7),
            array(8),
            array(200)
        );
    }

    public function providerForNotMaxLength()
    {
        return array(
            array(6),
            array(1),
            array(-1)
        );
    }
}
