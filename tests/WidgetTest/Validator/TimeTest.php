<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class TimeTest extends TestCase
{
    /**
     * @dataProvider providerForTime
     */
    public function testTime($input)
    {
        $this->assertTrue($this->isTime($input));
    }

    /**
     * @dataProvider providerForNotTime
     */
    public function testNotTime($input)
    {
        $this->assertFalse($this->isTime($input));
    }

    public function providerForTime()
    {
        return array(
            array('00:00:00'),
            array('00:00'),
            array('23:59:59'),
        );
    }

    public function providerForNotTime()
    {
        return array(
            array('24:00:00'),
            array('23:60:00'),
            array('23:59:61'),
            array('61:00'),
            array('01:01:01:01')
        );
    }
}
