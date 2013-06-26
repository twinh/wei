<?php

namespace WidgetTest\Validator;

class CallbackTest extends TestCase
{    
    public function setUp()
    {
        parent::setUp();
        
        $this->inputTestOptions['fn'] = function(){};
    }
    
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
