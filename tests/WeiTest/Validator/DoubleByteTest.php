<?php

namespace WeiTest\Validator;

class DoubleByteTest extends TestCase
{
    /**
     * @dataProvider providerForDoubleByte
     */
    public function testDoubleByte($input)
    {
        $this->assertTrue($this->isDoubleByte($input));
    }

    /**
     * @dataProvider providerForNotDoubleByte
     */
    public function testNotDoubleByte($input)
    {
        $this->assertFalse($this->isDoubleByte($input));
    }

    public function providerForDoubleByte()
    {
        return array(
            array('中文'),
            array('汉字'),
            array('姓名'),
            array('；１２３'),
            array('にほんご'), // Japanese language
            array('조선어'), // Korean language
            array('āōêīūǖ'),
        );
    }

    public function providerForNotDoubleByte()
    {
        return array(
            array('abc'),
            array('123'),
            array('中文english'),
        );
    }
}
