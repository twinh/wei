<?php

namespace WeiTest\Fixtures\app\middleware;

class Before extends \Wei\Base
{
    public function __invoke($next)
    {
        wei()->response->setContent('Before Middleware');
    }
}