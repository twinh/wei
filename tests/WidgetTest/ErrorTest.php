<?php

namespace WidgetTest;

class ErrorTest extends TestCase
{
    public function testException()
    {
        $this->expectOutputRegex('/testException/');
        
        $this->trigger('exception', array(new \Exception('testException')));
    }
    
    public function testAjaxException()
    {
        $this->server->set('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');
        
        ob_start();
        $this->trigger('exception', array(new \Exception('ajax error')));
        $json = json_decode(ob_get_clean());
        var_dump($json);
        $this->assertSame(json_last_error(), JSON_ERROR_NONE);
    }
}