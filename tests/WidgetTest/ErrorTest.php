<?php

namespace WidgetTest;

/**
 * It's hard to test error, some of the tests is use for code coverage only
 */
class ErrorTest extends TestCase
{
    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }
    
    public function createErrorView($message, $code = 0)
    {
        $exception = new \Exception($message, $code);
        $event = new \Widget\Event\Event(array(
            'widget' => $this->widget,
            'name' => 'exception'
        ));
        
        $this->error->handleException($event, $this->widget, $exception);
    }
    
    public function testException()
    {
        $this->expectOutputRegex('/test exception/');
        
        $this->createErrorView('test exception');
    }
    
    public function testAjaxException()
    {
        $this->server->set('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');

        ob_start();
        $this->createErrorView('ajax error', 250);
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
    
    /**
     * @expectedException \ErrorException
     */
    public function testErrorToException()
    {
        $array['key'];
    }
    
    public function testInvoker()
    {
        $this->error(function(){});
        
        $this->assertTrue($this->eventManager->has('exception'));
    }

    public function testhandleException()
    {
        // Output error like Method "Widget\Widget->debug" or widget "debug" (class "Widget\Debug") not found, called in file ...
        $this->expectOutputRegex('/Debug/');
        
        // Change logger to widget to make error in handleException
        $this->error->logger = $this->widget;
        
        $event = new \Widget\Event\Event(array(
            'widget' => $this->widget,
            'name' => 'exception'
        ));
        
        $exception = new \Exception();
        
        $this->error->handleException($event, $this->widget, $exception);
    }
}