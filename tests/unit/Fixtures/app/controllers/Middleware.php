<?php

namespace WeiTest\Fixtures\app\controllers;

class Middleware extends \Wei\Base
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware('WeiTest\Fixtures\app\middleware\Before', array('only' => 'before'));

        $this->middleware('WeiTest\Fixtures\app\middleware\After', array('only' => 'after'));

        $this->middleware('WeiTest\Fixtures\app\middleware\Around', array('only' => 'around'));
    }

    public function before()
    {
        return 'not execute';
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