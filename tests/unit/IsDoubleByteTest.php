<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDoubleByteTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForDoubleByte
     * @param mixed $input
     */
    public function testDoubleByte($input)
    {
        $this->assertTrue($this->isDoubleByte($input));
    }

    /**
     * @dataProvider providerForNotDoubleByte
     * @param mixed $input
     */
    public function testNotDoubleByte($input)
    {
        $this->assertFalse($this->isDoubleByte($input));
    }

    public static function providerForDoubleByte()
    {
        return [
            ['中文'],
            ['汉字'],
            ['姓名'],
            ['；１２３'],
            ['にほんご'], // Japanese language
            ['조선어'], // Korean language
            ['āōêīūǖ'],
        ];
    }

    public static function providerForNotDoubleByte()
    {
        return [
            ['abc'],
            ['123'],
            ['中文english'],
        ];
    }
}
