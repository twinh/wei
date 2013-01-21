<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

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
            'endsWith' => array(
                'findMe' => '@gmail.com'
            )
        )));
    }
}
