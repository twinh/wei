<?php

namespace WeiTest\Fixtures\app\controllers;

/**
 * @property \Wei\Response $response
 */
class Middleware extends \Wei\BaseController
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware('WeiTest\Fixtures\app\middleware\All');

        $this->middleware('WeiTest\Fixtures\app\middleware\Only', array('only' => 'only'));

        $this->middleware('WeiTest\Fixtures\app\middleware\Except', array('except' => array(
            'only', 'before', 'after', 'around'
        )));

        $this->middleware('WeiTest\Fixtures\app\middleware\Before', array('only' => 'before'));

        $this->middleware('WeiTest\Fixtures\app\middleware\After', array('only' => 'after'));

        $this->middleware('WeiTest\Fixtures\app\middleware\Around', array('only' => 'around'));

        $this->middleware('WeiTest\Fixtures\app\middleware\Stack', array('only' => 'stack'));
        $this->middleware('WeiTest\Fixtures\app\middleware\Stack2', array('only' => 'stack'));
        $this->middleware('WeiTest\Fixtures\app\middleware\Stack3', array('only' => 'stack'));
    }

    public function onlyAction()
    {
        return 'only';
    }

    public function exceptAction()
    {
        return 'except';
    }

    public function beforeAction()
    {
        return 'before';
    }

    public function afterAction()
    {
        return 'after';
    }

    public function aroundAction()
    {
        return 'around';
    }

    public function stackAction()
    {
        return $this->response->setContent($this->response->getContent() . 'stack');
    }

    protected function middleware($name, array $options = array())
    {
        $this->middleware[$name] = $options;
    }
}