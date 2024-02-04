<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTinyCharTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLength
     * @param mixed $input
     */
    public function testLength($input)
    {
        $this->assertTrue($this->isTinyChar($input));
    }

    /**
     * @dataProvider providerForNotLength
     * @param mixed $input
     */
    public function testNotLength($input)
    {
        $this->assertFalse($this->isTinyChar($input));
    }

    public static function providerForLength()
    {
        return [
            ['i♥u4'],
            [str_repeat('1', 255)],
            [str_repeat('😊', 255)],
        ];
    }

    public static function providerForNotLength()
    {
        return [
            [str_repeat('1', 256)],
            [str_repeat('😊', 255) . '1'],
        ];
    }
}
