<?php

namespace WeiTest\Fixtures\app\middleware;

class Stack extends Base
{
    public function __invoke($next)
    {
        $response = $this->response;

        $response->setContent($response->getContent() . 'start1-');

        $res = $next();

        $response->setContent($response->getContent() . '-end1');

        return $res;
    }
}