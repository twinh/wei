<?php

namespace WeiTest\Fixtures\app\middleware;

class Only extends \Wei\Base
{
    public function __invoke($next)
    {
        $next();
    }
}