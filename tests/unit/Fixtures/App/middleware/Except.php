<?php

namespace WeiTest\Fixtures\app\middleware;

class Except extends Base
{
    public function __invoke($next)
    {
        return $next();
    }
}