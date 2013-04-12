<?php

namespace WidgetTest\AppTest\Controller;

class ForwardController extends \Widget\AbstractWidget
{
    public function targetAction()
    {
        return 'target';
    }
}