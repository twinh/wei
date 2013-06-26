<?php

namespace WidgetTest\AppTest;

class Test extends \Widget\AbstractWidget
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

    public function forwardActionAction()
    {
        return $this->app->forward('target');
    }

    public function forwardControllerAction()
    {
        return $this->app->forward('target', 'forward');
    }

    public function targetAction()
    {
        return 'target';
    }
}