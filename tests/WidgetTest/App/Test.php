<?php

namespace WidgetTest\App;

class Test extends \Widget\Base
{
    public function test()
    {
        return 'test';
    }

    public function returnArray()
    {
        return array(
            'key' => 'value'
        );
    }

    public function returnResponse()
    {
        $this->response->setContent('response content');

        return $this->response;
    }

    public function returnUnexpectedType()
    {
        return new \stdClass();
    }

    public function dispatchBreak()
    {
        $this->doSomethingNotInActions();

        throw new \RuntimeException('You can\'t see me');
    }

    public function doSomethingNotInActions()
    {
        echo 'stop';

        $this->app->preventPreviousDispatch();

        throw new \RuntimeException('You can\'t see me too');
    }

    public function forwardAction()
    {
        return $this->app->forward('target');
    }

    public function forwardController()
    {
        return $this->app->forward('target', 'forward');
    }

    public function target()
    {
        return 'target';
    }
}