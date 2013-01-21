<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

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
}
