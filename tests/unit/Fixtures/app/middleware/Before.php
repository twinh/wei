<?php

namespace WeiTest\Fixtures\app\middleware;

class Before extends \Wei\Base
{
    public function __invoke($next)
    {
        return wei()->response->setContent('Before Middleware');
    }
}