<?php

namespace WeiTest\Validator;

class NaturalNumberTest extends TestCase
{
    /**
     * @dataProvider providerForNaturalNumber
     */
    public function testNaturalNumber($input)
    {
        $this->assertTrue($this->isNaturalNumber($input));
    }

    /**
     * @dataProvider providerForNotNaturalNumber
     */
    public function testNotNaturalNumber($input)
    {
        $this->assertFalse($this->isNaturalNumber($input));
    }

    public function providerForNaturalNumber()
    {
        return array(
            array('0'),
            array(0),
            array(1),
            array('11'),
            array('100'),
            array('+1'),
            array(+1),
            array('+0'),
            array(+0),
            array(-0),
            array('-0')
        );
    }

    public function providerForNotNaturalNumber()
    {
        return array(
            array('0.1'),
            array('a bcdefg'),
            array('1 23456'),
            array('string'),
            array(-1),
            array('-1'),
        );
    }
}