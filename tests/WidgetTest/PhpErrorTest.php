<?php

namespace WidgetTest;

class PhpErrorTest extends TestCase
{
    public function setUp()
    {
        // Avoid "Undefined index: REQUEST_URI" error
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'localhost';
        
        parent::setUp();
    }
    
    public function testInvoker()
    {
        $this->assertInstanceOf('\php_error\ErrorHandler', $this->phpError());
    }
}