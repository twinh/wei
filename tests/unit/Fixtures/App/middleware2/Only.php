<?php

namespace WeiTest\Fixtures\App\Middleware;

class Only extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}
