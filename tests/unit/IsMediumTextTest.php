<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMediumTextTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMediumTextVal
     * @param mixed $input
     */
    public function testStringVal($input)
    {
        $this->assertTrue($this->isMediumText($input));
    }

    public static function providerForMediumTextVal()
    {
        return [
            [''],
            ['123'],
            [str_repeat('1', 16777215)],
            [str_repeat('我', 16777215 / 3)],
            [str_repeat('🙂', (int) (16777215 / 4))],
        ];
    }

    /**
     * @dataProvider providerForNotMediumTextVal
     * @param mixed $input
     */
    public function testNotStringVal($input)
    {
        $this->assertFalse($this->isMediumText($input));
    }

    public static function providerForNotMediumTextVal()
    {
        return [
            [str_repeat('1', 16777215 + 1)],
            [str_repeat('我', 16777215 / 3) . '1'],
            [str_repeat('🙂', (int) (16777215 / 4)) . '1234'],
        ];
    }
}
