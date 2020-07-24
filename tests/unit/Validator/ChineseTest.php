<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class ChineseTest extends TestCase
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

    public function providerForChinese()
    {
        return [
            ['中文'],
            ['汉字'],
            ['姓名'],
        ];
    }

    public function providerForNotChinese()
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
