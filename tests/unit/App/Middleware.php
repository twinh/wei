<?php

namespace WeiTest\App;

class Middleware extends \Wei\Base
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware('\WeiTest\App\Middleware\HelloWorld');
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