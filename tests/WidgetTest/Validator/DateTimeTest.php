<?php

namespace WidgetTest\Validator;

class DateTimeTest extends TestCase
{
    /**
     * @dataProvider providerForDateTime
     */
    public function testDateTime($input, $format = null)
    {
        $this->assertTrue($this->isDateTime($input, $format));
    }

    /**
     * @dataProvider providerForNotDateTime
     */
    public function testNotDateTime($input, $format = null)
    {
        $this->assertFalse($this->isDateTime($input, $format));
    }

    public function providerForDateTime()
    {
        return array(
            array('1000-01-01 00:00:00'),
            array('3000-01-01 00:00:50'),
            array('2012-02-29 23:59:59'),
            array('2013-02-29 24:00:00'), // => 2013-03-02 00:00:00
        );
    }
    
    public function providerForNotDateTime()
    {
        return array(
            array('2013-02-29 24:00:00', 'Y-m-d H:i:s'),
            array('2013-01-32 23:60:00'),
            array('2013-00-00 23:59:61'),
            array('2012 61:00')
        );
    }
    
    public function testBeforeAndAfter()
    {
        $this->assertTrue($this->is('date', '2013-02-19', array(
            'before' => '2013-03-01',
            'after' => '2013-01-01',
        )));
        
        $this->assertFalse($this->is('date', '2013-02-19', array(
            'before' => '2013-01-01'
        )));
        
        $this->assertFalse($this->is('date', '2013-02-19', array(
            'after' => '2013-03-01'
        )));
    }
}
