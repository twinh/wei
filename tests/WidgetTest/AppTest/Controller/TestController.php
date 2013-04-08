<?php

namespace WidgetTest\AppTest\Controller;

class TestController extends \Widget\AbstractWidget
{
    public function testAction()
    {
        return 'test';
    }
    
    public function returnArrayAction()
    {
        return array(
            'key' => 'value'
        );
    }
        
    public function returnResponseAction()
    {
        $this->response->setContent('response content');
        
        return $this->response;
    }
    
    public function returnUnexpectedTypeAction()
    {
        return new \stdClass();
    }
    
    public function dispatchBreakAction()
    {
        $this->doSomethingNotInActions();
        
        throw new \Widget\Exception\RuntimeException('You can\'t see me');
    }
    
    public function doSomethingNotInActions()
    {
        echo 'stop';
        
        $this->app->preventPreviousDispatch();
        
        throw new \Widget\Exception\RuntimeException('You can\'t see me too');
    }
}