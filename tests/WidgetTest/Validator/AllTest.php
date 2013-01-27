<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class AllTest extends TestCase
{
    public function testAll()
    {
        $this->assertTrue($this->isAll(array(
            'apple', 'pear', 'orange',
        ), array(
            'in' => array('apple', 'pear', 'orange')
        )));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $this->isAll('string', array());
    }
    
    public function testNotAll()
    {
        $this->assertFalse($this->isAll(array(
            'apple', 'pear',
        ), array(
            'in' => array('apple', 'pear', 'orange'),
            'length' => array(
                'min' => 5,
                'max' => 10
            )
        )));
    }
}
