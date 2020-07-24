<?php

namespace WeiTest\Fixtures\App\Middleware;

class ReturnArray extends Base
{
    public function __invoke()
    {
        return [
            'arrayForView' => 'arrayForView',
        ];
    }
}
