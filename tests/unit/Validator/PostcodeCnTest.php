<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class PostcodeCnTest extends TestCase
{
    /**
     * @dataProvider providerForPostcodeCn
     * @param mixed $input
     */
    public function testPostcodeCn($input)
    {
        $this->assertTrue($this->isPostcodeCn($input));
    }

    /**
     * @dataProvider providerForNotPostcodeCn
     * @param mixed $input
     */
    public function testNotPostcodeCn($input)
    {
        $this->assertFalse($this->isPostcodeCn($input));
    }

    public function providerForPostcodeCn()
    {
        return [
            ['123456'],
            ['515638'],
        ];
    }

    public function providerForNotPostcodeCn()
    {
        return [
            ['1234567'],
            ['0234567'],
        ];
    }
}
