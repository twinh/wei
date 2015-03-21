<?php

namespace WeiTest\Fixtures\app\middleware;

class Around extends \Wei\Base
{
    public function __invoke($next)
    {
        $response = wei()->response->setStatusCode(404);

        $next();

        return $response->setContent('Not Found');
    }
}