<?php

namespace WeiTest\Fixtures\app\middleware;

class All extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}