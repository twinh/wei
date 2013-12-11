<?php

namespace WeiTest\Validator;

class IdCardMoTest extends TestCase
{
    /**
     * @dataProvider providerForIdCardMo
     */
    public function testIdCardMo($input)
    {
        $this->assertTrue($this->isIdCardMo($input));
    }

    /**
     * @dataProvider providerForNotIdCardMo
     */
    public function testNotIdCardMo($input)
    {
        $this->assertFalse($this->isIdCardMo($input));
    }

    public function providerForIdCardMo()
    {
        return array(
            array('11111111'),
            array('55555555'),
            array('77777777')
        );
    }

    public function providerForNotIdCardMo()
    {
        return array(
            array('00000000'), // first digit should be 1,5,7
            array('22222222'),
            array('33333333'),
            array('44444444'),
            array('66666666'),
            array('88888888'),
            array('99999999'),
            array('1234567'), // length
        );
    }
}
