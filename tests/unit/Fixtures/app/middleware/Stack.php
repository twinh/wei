<?php

namespace WeiTest\Fixtures\app\middleware;

class Stack extends \Wei\Base
{
    public function __invoke($next)
    {
        /** @var \Wei\Response $response */
        $response = $this->response;

        $response->setContent($response->getContent() . 'start1-');

        $response = $next();

        $response->setContent($response->getContent() . '-end1');
    }
}