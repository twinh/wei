<?php

namespace WeiTest\Validator;

class DateTest extends TestCase
{
    /**
     * @dataProvider providerForDate
     */
    public function testDate($input)
    {
        $this->assertTrue($this->isDate($input));
    }

    /**
     * @dataProvider providerForNotDate
     */
    public function testNotDate($input)
    {
        $this->assertFalse($this->isDate($input));
    }

    public function providerForDate()
    {
        return array(
            array('2013-01-13'),
            array('1000-01-01'),
            array('3000-01-01'),
            array('2012-02-29'),
        );
    }

    public function providerForNotDate()
    {
        return array(
            array('0'),
            array(0),
            array(0.0),
            array('2013-02-29'),
            array('2013-01-32'),
            array('2013-00-00'),
            array('2012')
        );
    }
}
