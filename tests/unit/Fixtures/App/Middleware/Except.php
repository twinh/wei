<?php

namespace WeiTest\Fixtures\App\Middleware;

class Except extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}
