<?php

namespace WeiTest\Fixtures\App\Middleware;

class Before extends Base
{
    public function __invoke($next)
    {
        return $this->response->setContent('Before Middleware');
    }
}
