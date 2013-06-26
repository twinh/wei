<?php

namespace WidgetTest\Validator;

class QQTest extends TestCase
{
    /**
     * @dataProvider providerForQQ
     */
    public function testQQ($input)
    {
        $this->assertTrue($this->is('QQ', $input));
        
        $this->assertFalse($this->is('notQQ', $input));
    }

    /**
     * @dataProvider providerForNotQQ
     */
    public function testNotQQ($input)
    {
        $this->assertFalse($this->is('QQ', $input));
        
        $this->assertTrue($this->is('notQQ', $input));
    }

    public function providerForQQ()
    {
        return array(
            array('1234567'),
            array('1234567'),
            array('123456789'),
        );
    }

    public function providerForNotQQ()
    {
        return array(
            array('1000'), // Too short
            array('011111'), // Should not start with zero
            array('134.433'),
            array('not digit'),
        );
    }
}
