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
        
        $this->assertTrue($this->isCallback('data', array(
            'fn' => function(){
                return true;
            }
        )));
    }
    
    /**
     * @expectedException Widget\UnexpectedTypeException
     */
    public function testParameter2NotInvalidException()
    {
        $this->isCallback('data', 'not callable');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotCallableException()
    {
        $this->isCallback('data', array(
            'fn' => 'not callable'
        ));
    }
    
    //public function tes
    
    public function testNotCallback()
    {
        $this->assertFalse($this->isCallback('data', function(){
            return false;
        }));
        
        $this->assertFalse($this->isCallback('data', function(){
            // convert to boolen(false)
            return  null;
        }));
    }
}
