<?php

namespace WeiTest\Fixtures\app\controllers;

class Test extends \Wei\Base
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
        return $this->app->forward('forward', 'target');
    }

    public function forwardController()
    {
        return $this->app->forward('forward');
    }

    public function target()
    {
        return 'target';
    }

    public function returnInt()
    {
        return 123;
    }

    public function returnNull()
    {
        return null;
    }

    public function returnFloat()
    {
        return 1.1;
    }

    public function returnTrue()
    {
        return true;
    }

    public function returnFalse()
    {
        return false;
    }

    public function parameter($req, $res)
    {
        return $res($req['id']);
    }

    public function _action()
    {
        throw new \Exception('Never call me');
    }

    protected function protect()
    {
        throw new \Exception('Never call me');
    }

    public function caseInsensitive()
    {
        return __FUNCTION__;
    }
}
