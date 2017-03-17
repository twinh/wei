<?php

namespace WeiTest\Fixtures\app\middleware;

class Stack3 extends Base
{
    public function __invoke($next)
    {
        $response = $this->response;

        $response->setContent($response->getContent() . 'start3-');

        $res = $next();

        $response->setContent($response->getContent() . '-end3');

        return $res;
    }
}