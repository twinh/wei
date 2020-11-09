<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIdCardMoTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIdCardMo
     * @param mixed $input
     */
    public function testIdCardMo($input)
    {
        $this->assertTrue($this->isIdCardMo($input));
    }

    /**
     * @dataProvider providerForNotIdCardMo
     * @param mixed $input
     */
    public function testNotIdCardMo($input)
    {
        $this->assertFalse($this->isIdCardMo($input));
    }

    public function providerForIdCardMo()
    {
        return [
            ['11111111'],
            ['55555555'],
            ['77777777'],
        ];
    }

    public function providerForNotIdCardMo()
    {
        return [
            ['00000000'], // first digit should be 1,5,7
            ['22222222'],
            ['33333333'],
            ['44444444'],
            ['66666666'],
            ['88888888'],
            ['99999999'],
            ['1234567'], // length
        ];
    }
}
