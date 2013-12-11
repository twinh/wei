<?php

namespace WeiTest\Validator;

class OneOfTest extends TestCase
{
    public function testOneOf()
    {
        $this->assertTrue($this->isOneOf('13', array(
            'type' => 'int',
            'alnum' => true
        )));
    }
    
    public function testNotOneOf()
    {
        $this->assertFalse($this->isOneOf('13', array(
            'email' => true,
            'length' => array(3, 6)
        )));
    }
}
