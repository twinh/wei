<?php

namespace WidgetTest;

class ErrorTest extends TestCase
{
    public function testException()
    {
        $this->expectOutputRegex('/testException/');
        
        $this->trigger('exception', array(new \Exception('testException')));
    }
}