<?php

namespace WeiTest\Fixtures\app\middleware;

class Stack3 extends \Wei\Base
{
    public function __invoke($next)
    {
        /** @var \Wei\Response $response */
        $response = $this->response;

        $response->setContent($response->getContent() . 'start3-');

        $response = $next();

        $response->setContent($response->getContent() . '-end3');

        return $response;
    }
}