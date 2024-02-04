<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTextTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTextVal
     * @param mixed $input
     */
    public function testStringVal($input)
    {
        $this->assertTrue($this->isText($input));
    }

    /**
     * @dataProvider providerForNotTextVal
     * @param mixed $input
     */
    public function testNotStringVal($input)
    {
        $this->assertFalse($this->isText($input));
    }

    public static function providerForTextVal()
    {
        return [
            [''],
            ['123'],
            [str_repeat('1', 65535)],
            [str_repeat('我', 65535 / 3)],
            [str_repeat('🙂', (int) (65535 / 4))],
        ];
    }

    public static function providerForNotTextVal()
    {
        return [
            [str_repeat('1', 65535) . '1'],
            [str_repeat('我', 65535 / 3) . '1'],
            [str_repeat('🙂', (int) (65535 / 4)) . '1234'],
        ];
    }
}
