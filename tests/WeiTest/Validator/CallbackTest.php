<?php

namespace WeiTest\Validator;

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
