<?php

namespace WidgetTest;

class UrlDebuggerTest extends TestCase
{
    public function testAjax()
    {
        $this->request->set('_ajax', true);
        $this->urlDebugger->inject();
        $this->assertTrue($this->request->isAjax());
    }

    public function testMethod()
    {
        $this->request->set('_method', 'PUT');
        $this->urlDebugger->inject();
        $this->assertTrue($this->request->isMethod('PUT'));
    }
}