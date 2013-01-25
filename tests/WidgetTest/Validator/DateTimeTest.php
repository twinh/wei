<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @dataProvider providerForDateTime
     */
    public function testDateTime($input)
    {
        $this->assertTrue($this->isDateTime($input));
    }

    /**
     * @dataProvider providerForNotDateTime
     */
    public function testNotDateTime($input)
    {
        $this->assertFalse($this->isDateTime($input));
    }

    public function providerForDateTime()
    {
        return array(
            array('1000-01-01 00:00:00'),
            array('3000-01-01 00:00:50'),
            array('2012-02-29 23:59:59'),
        );
    }
    
    public function providerForNotDateTime()
    {
        return array(
            array('2013-02-29 24:00:00'),
            array('2013-01-32 23:60:00'),
            array('2013-00-00 23:59:61'),
            array('2012 61:00')
        );
    }
}
