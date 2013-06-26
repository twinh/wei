<?php

namespace WidgetTest;

class UrlDebuggerTest extends TestCase
{
    public function testInvoker()
    {
        $this->assertInstanceOf('\Widget\UrlDebugger', $this->urlDebugger());
    }
    
    public function testAjax()
    {
        $this->query['_ajax'] = true;
        $this->urlDebugger->inject();
        $this->assertTrue($this->request->inAjax());
    }
    
    public function testMethod()
    {
        $this->query['_method'] = 'PUT';
        $this->urlDebugger->inject();
        $this->assertTrue($this->request->inMethod('PUT'));
    }
}