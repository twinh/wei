<?php

namespace WeiTest\Fixtures\app\middleware;

class ReturnString extends Base
{
    public function __invoke()
    {
        return 'string';
    }
}