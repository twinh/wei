<?php

namespace WeiTest\Fixtures\App\Middleware;

class After extends Base
{
    public function __invoke($next)
    {
        return 'After Middleware';
    }
}
