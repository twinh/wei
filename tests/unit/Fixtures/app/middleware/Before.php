<?php

namespace WeiTest\Fixtures\app\middleware;

class Before extends Base
{
    public function __invoke($next)
    {
        return $this->response->setContent('Before Middleware');
    }
}