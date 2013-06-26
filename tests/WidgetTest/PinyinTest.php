<?php

namespace WidgetTest;

class PinyinTest extends TestCase
{
    /**
     * @dataProvider providerForPinyin
     */
    public function testInvoker($chinese, $pinyin)
    {
        $this->assertEquals($pinyin, $this->pinyin($chinese));
    }
    
    public function providerForPinyin()
    {
        return array(
            array('中文', 'zhongwen'),
            array('您好', 'ninhao')
        );
    }
}