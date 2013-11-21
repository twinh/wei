<?php

namespace WeiTest\Validator;

class PositiveIntegerTest extends TestCase
{
    /**
     * @dataProvider providerForPositiveInteger
     */
    public function testPositiveInteger($input)
    {
        $this->assertTrue($this->isPositiveInteger($input));
    }

    /**
     * @dataProvider providerForNotPositiveInteger
     */
    public function testNotPositiveInteger($input)
    {
        $this->assertFalse($this->isPositiveInteger($input));
    }

    public function providerForPositiveInteger()
    {
        return array(
            array(1),
            array('11'),
            array('100'),
            array('+1'),
            array(+1),
        );
    }

    public function providerForNotPositiveInteger()
    {
        return array(
            array('0'),
            array(0),
            array('0.1'),
            array('a bcdefg'),
            array('1 23456'),
            array('string'),
            array(-1),
            array('-1'),
        );
    }
}