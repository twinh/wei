<?php

namespace WeiTest\Fixtures\app\controllers;

class Middleware extends \Wei\Base
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware('WeiTest\Fixtures\app\middleware\Only', array('only' => 'only'));

        $this->middleware('WeiTest\Fixtures\app\middleware\except', array('except' => array(
            'only', 'before', 'after', 'around'
        )));

        $this->middleware('WeiTest\Fixtures\app\middleware\Before', array('only' => 'before'));

        $this->middleware('WeiTest\Fixtures\app\middleware\After', array('only' => 'after'));

        $this->middleware('WeiTest\Fixtures\app\middleware\Around', array('only' => 'around'));
    }

    public function only()
    {
        return 'only';
    }

    public function except()
    {
        return 'except';
    }

    public function before()
    {
        return 'before';
    }

    public function after()
    {
        return 'after';
    }

    public function around()
    {
        return 'around';
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }

    protected function middleware($name, array $options = array())
    {
        $this->middleware[$name] = $options;
    }
}