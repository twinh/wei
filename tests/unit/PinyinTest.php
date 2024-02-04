<?php

namespace WeiTest;

/**
 * @internal
 */
final class PinyinTest extends TestCase
{
    /**
     * @dataProvider providerForPinyin
     * @param mixed $chinese
     * @param mixed $pinyin
     */
    public function testInvoker($chinese, $pinyin)
    {
        $this->assertEquals($pinyin, $this->pinyin($chinese));
    }

    public static function providerForPinyin()
    {
        return [
            ['中文', 'zhongwen'],
            ['您好', 'ninhao'],
        ];
    }

    public function testSeparator()
    {
        $this->assertEquals('zhong wen', $this->pinyin('中文', ' '));
    }
}
