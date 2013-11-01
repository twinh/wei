<?php

namespace WeiTest\Validator;

class TimeTest extends TestCase
{
    /**
     * @dataProvider providerForTime
     */
    public function testTime($input, $format = null)
    {
        $this->assertTrue($this->isTime($input, $format));
    }

    /**
     * @dataProvider providerForNotTime
     */
    public function testNotTime($input, $format = null)
    {
        $this->assertFalse($this->isTime($input, $format));
    }

    public function providerForTime()
    {
        return array(
            array('00:00:00'),
            array('00:00', 'i:s'),
            array('23:59:59', 'H:i:s'),
        );
    }

    public function providerForNotTime()
    {
        return array(
            array('24:00:00'),
            array('23:60:00'),
            array('23:59:61'),
            array('61:00', 'i:s'),
            array('01:01:01:01')
        );
    }
}
