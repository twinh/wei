<?php

namespace WeiTest\Fixtures;

use Wei\Base;

class ServiceWithProvider extends Base
{
    protected $providers = [
        'request' => 'sub.request',
    ];
}
