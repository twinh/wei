<?php

namespace WeiTest\Validator;

class AlnumTest extends TestCase
{
    /**
     * @dataProvider providerForAlnum
     */
    public function testAlnum($input)
    {
        $this->assertTrue($this->isAlnum($input));
    }

    /**
     * @dataProvider providerForNotAlnum
     */
    public function testNotAlnum($input)
    {
        $this->assertFalse($this->isAlnum($input));
    }

    public function providerForAlnum()
    {
        return array(
            array('0'),
            array(0),
            array(0.0),
            array('abcedfg'),
            array('a2BcD3eFg4'),
            array('045fewwefds'),
        );
    }

    public function providerForNotAlnum()
    {
        return array(
            array('a bcdefg'),
            array('-213a bcdefg'),
        );
    }


    /**
     * @dataProvider providerForLocale
     */
    public function testLocale($locale, $message)
    {
        $t = new \Wei\T(array(
            'wei' => $this->wei,
            'locale' => $locale,
        ));

        $validator = new \Wei\Validator\Alnum(array(
            'wei' => $this->wei,
            't' => $t,
        ));

        $validator('1.2');

        $this->assertEquals($message, $validator->getFirstMessage());
    }

    public function providerForLocale()
    {
        return array(
            array('en', 'This value must contain letters (a-z) and digits (0-9)'),
            array('zh-CN', '该项只能由字母(a-z)和数字(0-9)组成')
        );
    }
}
