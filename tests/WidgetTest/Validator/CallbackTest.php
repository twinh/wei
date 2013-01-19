<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class CallbackTest extends TestCase
{
    public function testCallback()
    {
        $this->assertTrue($this->isCallback('data', function(){
            return true;
        }));
        
        $this->assertTrue($this->is('callback', 'data', function(){
            return true;
        }));
        
        $this->assertTrue($this->is(function(){
            return true;
        }, 'data'));
    }

    /**
     * @dataProvider providerForNotCallback
     */
//    public function testNotCallback($input)
//    {
//        $this->assertFalse($this->isCallback($input));
//    }

    public function providerForCallback()
    {
        return array(
            array('020-1234567'),
            array('0768-123456789'),
            // Callback number without city code
            array('1234567'),
            array('123456789'),
        );
    }

    public function providerForNotCallback()
    {
        return array(
            array('012345-1234567890'),
            array('010-1234567890'),
            array('123456'),
            array('not digit'),
        );
    }
}
