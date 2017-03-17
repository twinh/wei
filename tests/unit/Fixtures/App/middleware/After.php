<?php

namespace WeiTest\Fixtures\app\middleware;

class After extends Base
{
    public function __invoke($next)
    {
        return 'After Middleware';
    }
}