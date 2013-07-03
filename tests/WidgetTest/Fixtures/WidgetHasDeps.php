<?php

namespace WidgetTest\Fixtures;

use Widget\AbstractWidget;

class WidgetHasDeps extends AbstractWidget
{
    protected $deps = array(
        'request' => 'sub.request'
    );
}