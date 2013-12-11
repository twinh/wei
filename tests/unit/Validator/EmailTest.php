<?php

namespace WeiTest\Validator;

class EmailTest extends TestCase
{
    /**
     * @dataProvider providerForEmail
     */
    public function testEmail($input)
    {
        $this->assertTrue($this->isEmail($input));
    }

    /**
     * @dataProvider providerForNotEmail
     */
    public function testNotEmail($input)
    {
        $this->assertFalse($this->isEmail($input));
    }

    public function providerForEmail()
    {
        return array(
            array('abc@def.com'),
            array('abc@def.com.cn'),
            array('a@a.c'),
            array('a_b@c.com'),
            array('_a@b.com'),
            array('_@a.com'),
        );
    }

    public function providerForNotEmail()
    {
        return array(
            array('not email.com'),
            array('@a.com')
        );
    }
}
