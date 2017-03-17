<?php

namespace WeiTest\Fixtures\app\middleware;

class ReturnArray extends Base
{
    public function __invoke()
    {
        return array(
            'arrayForView' => 'arrayForView'
        );
    }
}