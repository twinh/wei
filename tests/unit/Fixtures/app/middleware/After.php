<?php

namespace WeiTest\Fixtures\app\middleware;

class After extends \Wei\Base
{
    public function __invoke($next)
    {
        /** @var \Wei\Response $response */
        $response = $next();

        return $response->setContent('After Middleware');
    }
}