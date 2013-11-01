<?php

namespace WeiTest\Validator;

class ChineseTest extends TestCase
{
    /**
     * @dataProvider providerForChinese
     */
    public function testChinese($input)
    {
        $this->assertTrue($this->isChinese($input));
    }

    /**
     * @dataProvider providerForNotChinese
     */
    public function testNotChinese($input)
    {
        $this->assertFalse($this->isChinese($input));
    }

    public function providerForChinese()
    {
        return array(
            array('中文'),
            array('汉字'),
            array('姓名'),
        );
    }

    public function providerForNotChinese()
    {
        return array(
            array('abc'),
            array('123'),
            array('中文english'),
            array('にほんご'), // Japanese language
            array('조선어'), // Korean language
        );
    }
}
