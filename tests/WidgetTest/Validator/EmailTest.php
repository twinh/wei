<?php

namespace WidgetTest\Validator;

class EmailTest extends TestCase
{
    /**
     * @dataProvider providerForEmail
     */
    public function testEmail($input)
    {
        $this->assertTrue($this->is('email', $input));
        
        $this->assertFalse($this->is('notEmail', $input));
    }

    /**
     * @dataProvider providerForNotEmail
     */
    public function testNotEmail($input)
    {
        $this->assertFalse($this->is('email', $input));
        
        $this->assertTrue($this->is('notEmail', $input));
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
