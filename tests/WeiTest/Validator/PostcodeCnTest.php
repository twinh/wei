<?php

namespace WeiTest\Validator;

class PostcodeCnTest extends TestCase
{
    /**
     * @dataProvider providerForPostcodeCn
     */
    public function testPostcodeCn($input)
    {
        $this->assertTrue($this->isPostcodeCn($input));
    }

    /**
     * @dataProvider providerForNotPostcodeCn
     */
    public function testNotPostcodeCn($input)
    {
        $this->assertFalse($this->isPostcodeCn($input));
    }

    public function providerForPostcodeCn()
    {
        return array(
            array('123456'),
            array('515638')
        );
    }

    public function providerForNotPostcodeCn()
    {
        return array(
            array('1234567'),
            array('0234567'),
        );
    }
}
