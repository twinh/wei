<?php

namespace WidgetTest\Validator;

class NoneOfTest extends TestCase
{
    public function testNoneOf()
    {
        $this->assertTrue($this->isNoneOf('10000@qq.com', array(
            'digit' => true,
            'endsWith' => array(
                'findMe' => '@gmail.com'
            )
        )));
    }
    
    public function testNotNoneOf()
    {
        $this->assertFalse($this->isNoneOf('10000@qq.com', array(
            'email' => true,
            'endsWith' => array(
                'findMe' => '@gmail.com'
            )
        )));
    }
    
    public function testGetMessages()
    {
        $noneOf = $this->is->createRuleValidator('noneOf');
        
        $noneOf('abc', array(
            'equals' => 'abc',
            'alnum' => true
        ));
        
        // Returns multi messages as default
        $this->assertCount(2, $noneOf->getMessages());
    }
}
