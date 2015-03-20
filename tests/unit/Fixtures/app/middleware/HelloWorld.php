<?php

namespace WeiTest\Fixtures\app\middleware;

class HelloWorld extends \Wei\Base
{
    public function __invoke($next)
    {
        echo 'hello';

        $response = $next();

        echo 'world';
    }
}