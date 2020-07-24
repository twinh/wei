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

    public function providerForPinyin()
    {
        return [
            ['中文', 'zhongwen'],
            ['您好', 'ninhao'],
        ];
    }
}
