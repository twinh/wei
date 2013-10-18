<?php

namespace WidgetTest\Fixtures;

use Widget\Base;

class ServiceWithProvider extends Base
{
    protected $providers = array(
        'request' => 'sub.request'
    );
}