<?php

namespace WidgetTest;

class WidgetAwareTest extends TestCase
{
    public function setUp()
    {
        $this->object = new Fixtures\ClassExtendsWidgetAware;
        $this->object->setWidget($this->widget);
    }
    
    public function testGetWidget()
    {
        $this->assertEquals($this->request, $this->object->request);
    }
    
    public function testInvokeWidget()
    {
        $this->request->set(__METHOD__, 'test');
        $this->assertEquals($this->request(__METHOD__), $this->object->request(__METHOD__));
    }
}