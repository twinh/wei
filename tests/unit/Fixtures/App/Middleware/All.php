<?php

namespace WeiTest\Fixtures\App\Middleware;

class All extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}
