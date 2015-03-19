<?php

namespace WeiTest\App\Middleware;

class HelloWorld extends \Wei\Base
{
    public function __invoke($next)
    {
        echo 'hello';

        $response = $next();

        echo 'world';
    }
}