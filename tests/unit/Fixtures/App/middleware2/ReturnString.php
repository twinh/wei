<?php

namespace WeiTest\Fixtures\App\Middleware;

class ReturnString extends Base
{
    public function __invoke()
    {
        return 'string';
    }
}
