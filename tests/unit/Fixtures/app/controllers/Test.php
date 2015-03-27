<?php

namespace WeiTest\Fixtures\app\controllers;

class Test extends \Wei\BaseController
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
        $this->doSomethingNotInActionsAction();

        throw new \RuntimeException('You can\'t see me');
    }

    public function doSomethingNotInActionsAction()
    {
        echo 'stop';

        $this->app->preventPreviousDispatch();

        throw new \RuntimeException('You can\'t see me too');
    }

    public function forwardActionAction()
    {
        return $this->app->forward('forward', 'target');
    }

    public function forwardControllerAction()
    {
        return $this->app->forward('forward');
    }

    public function targetAction()
    {
        return 'target';
    }

    public function returnIntAction()
    {
        return 123;
    }

    public function returnNullAction()
    {
        return null;
    }

    public function returnFloatAction()
    {
        return 1.1;
    }

    public function returnTrueAction()
    {
        return true;
    }

    public function returnFalseAction()
    {
        return false;
    }

    public function parameterAction($req, $res)
    {
        return $req['id'];
    }

    public function _actionAction()
    {
        throw new \Exception('Never call me');
    }

    public function caseInsensitiveAction()
    {
        return __FUNCTION__;
    }
}
