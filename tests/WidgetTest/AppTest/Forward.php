<?php

namespace WidgetTest\AppTest;

class Forward extends \Widget\AbstractWidget
{
    public function targetAction()
    {
        return 'target';
    }
}