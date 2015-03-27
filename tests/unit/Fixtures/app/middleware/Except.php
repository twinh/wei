<?php

namespace WeiTest\Fixtures\app\middleware;

class Except extends \Wei\Base
{
    public function __invoke($next)
    {
        $next();
    }
}