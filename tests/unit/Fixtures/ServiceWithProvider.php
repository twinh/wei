<?php

namespace WeiTest\Fixtures;

use Wei\Base;

class ServiceWithProvider extends Base
{
    protected $providers = [
        'req' => 'sub:req',
    ];
}
