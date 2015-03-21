<?php

namespace WeiTest\Fixtures\app\middleware;

class Stack2 extends \Wei\Base
{
    public function __invoke($next)
    {
        /** @var \Wei\Response $response */
        $response = $this->response;

        $response->setContent($response->getContent() . 'start2-');

        $response = $next();

        $response->setContent($response->getContent() . '-end2');

        return $response;
    }
}