<?php

namespace WidgetTest\Fixtures;

use Widget\Base;

class WidgetHasDeps extends Base
{
    protected $deps = array(
        'request' => 'sub.request'
    );
}