<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class EmailTest extends TestCase
{
    /**
     * @dataProvider providerForEmail
     * @param mixed $input
     */
    public function testEmail($input)
    {
        $this->assertTrue($this->isEmail($input));
    }

    /**
     * @dataProvider providerForNotEmail
     * @param mixed $input
     */
    public function testNotEmail($input)
    {
        $this->assertFalse($this->isEmail($input));
    }

    public function providerForEmail()
    {
        return [
            ['abc@def.com'],
            ['abc@def.com.cn'],
            ['a@a.c'],
            ['a_b@c.com'],
            ['_a@b.com'],
            ['_@a.com'],
        ];
    }

    public function providerForNotEmail()
    {
        return [
            ['not email.com'],
            ['@a.com'],
        ];
    }
}
