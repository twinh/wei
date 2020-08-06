<?php

namespace WeiTest\Fixtures\App\Middleware;

class Stack3 extends Base
{
    public function __invoke($next)
    {
        $response = $this->res;

        $response->setContent($response->getContent() . 'start3-');

        $res = $next();

        $response->setContent($response->getContent() . '-end3');

        return $res;
    }
}
