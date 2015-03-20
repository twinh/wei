<?php

namespace WeiTest\Fixtures\app\controllers;

class Middleware extends \Wei\Base
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware('WeiTest\Fixtures\app\middleware\HelloWorld');
    }

    public function index()
    {
        echo ' middleware ';
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