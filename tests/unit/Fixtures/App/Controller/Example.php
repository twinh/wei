<?php

namespace WeiTest\Fixtures\App\Controller;

class Example extends \Wei\BaseController
{
    public function exampleAction()
    {
        return 'test';
    }

    public function returnArrayAction()
    {
        return [
            'key' => 'value',
        ];
    }

    public function returnResponseAction()
    {
        $this->res->setContent('response content');

        return $this->res;
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

    public function forwardParamsAction()
    {
        return $this->app->forward('forward', 'params', ['param' => __FUNCTION__]);
    }

    public function targetAction()
    {
        return 'target';
    }

    public function returnIntAction()
    {
        return (int) 123;
    }

    public function returnNullAction()
    {
        return null;
    }

    public function returnFloatAction()
    {
        return (float) 1.1;
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

    // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    public function _actionAction()
    {
        throw new \Exception('Never call me');
    }

    public function caseInsensitiveAction()
    {
        return __FUNCTION__;
    }
}
