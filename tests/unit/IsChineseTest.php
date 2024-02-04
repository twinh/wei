<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsChineseTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForChinese
     * @param mixed $input
     */
    public function testChinese($input)
    {
        $this->assertTrue($this->isChinese($input));
    }

    /**
     * @dataProvider providerForNotChinese
     * @param mixed $input
     */
    public function testNotChinese($input)
    {
        $this->assertFalse($this->isChinese($input));
    }

    public static function providerForChinese()
    {
        return [
            ['中文'],
            ['汉字'],
            ['姓名'],
        ];
    }

    public static function providerForNotChinese()
    {
        return [
            ['abc'],
            ['123'],
            ['中文english'],
            ['にほんご'], // Japanese language
            ['조선어'], // Korean language
        ];
    }
}
