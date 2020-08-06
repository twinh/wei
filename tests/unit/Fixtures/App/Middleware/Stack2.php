<?php

namespace WeiTest\Fixtures\App\Middleware;

class Stack2 extends Base
{
    public function __invoke($next)
    {
        $response = $this->res;

        $response->setContent($response->getContent() . 'start2-');

        $res = $next();

        $response->setContent($response->getContent() . '-end2');

        return $res;
    }
}
