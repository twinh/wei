<?php

namespace WeiTest\Fixtures\app\middleware;

class Only extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}