<?php

namespace WeiTest\Fixtures\app\middleware;

class All extends \Wei\Base
{
    public function __invoke($next)
    {
        $next();
    }
}