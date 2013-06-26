<?php

namespace WidgetTest\Validator;

class TldTest extends TestCase
{
    /**
     * @dataProvider providerForTld
     */
    public function testTld($input, $format = null)
    {
        $this->assertTrue($this->isTld($input, $format));
    }

    /**
     * @dataProvider providerForNotTld
     */
    public function testNotTld($input, $format = null)
    {
        $this->assertFalse($this->isTld($input, $format));
    }

    public function providerForTld()
    {
        return array(
            array('com'),
            array('COM'),
            array('cn'),
            array('us'),
            array('xn--fiqs8S'), // 中国
        );
    }

    public function providerForNotTld()
    {
        return array(
            array('abc'),
            array('xn'),
            array('xn--'),
            array('xn--abc')
        );
    }
}
