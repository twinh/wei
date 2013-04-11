<?php

namespace WidgetTest;

class ErrorTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testException()
    {
        $this->expectOutputRegex('/testException/');
        
        $this->trigger('exception', array(new \Exception('testException')));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testAjaxException()
    {
        $this->server->set('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');

        ob_start();
        $this->trigger('exception', array(new \Exception('ajax error', -250)));
        $json = json_decode(ob_get_clean(), true);
        
        $this->assertSame(json_last_error(), JSON_ERROR_NONE);
        $this->assertEquals($json['message'], 'ajax error');
        $this->assertEquals($json['code'], -250);
        $this->assertContains('Threw by Exception in', $json['detail']);
    }
    
    public function testErrorHandler()
    {
        error_reporting(0);
        
        // Cover \Widget\Error::hanldeError
        $array['key'];
        
        error_reporting(-1);
    }
}