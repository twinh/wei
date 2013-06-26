<?php

namespace WidgetTest\Validator;

class AllOfTest extends TestCase
{
    public function testAllOf()
    {
        $this->assertTrue($this->isAllOf('10000@qq.com', array(
            'email' => true,
            'endsWith' => array(
                'findMe' => '@qq.com'
            )
        )));
    }
    
    public function testNotAllOf()
    {
        $this->assertFalse($this->isAllOf('10000@qq.com', array(
            'email' => true,
            'endsWith' => '@gmail.com'
        )));
    }
    
    public function testGetMessages()
    {
        $allOf = $this->is->createRuleValidator('allOf');
        
        $allOf('10000@qq.com', array(
            'email' => true,
            'endsWith' => '@gmail.com'
        ));
        
        // Returns single message as default
        $this->assertCount(1, $allOf->getMessages());
    }
}
