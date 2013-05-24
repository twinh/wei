<?php

namespace Demo;

use Widget\AbstractWidget;

class Hello extends AbstractWidget
{
    public function __invoke($world = 'World')
    {
        return 'Hello ' . $world;
    }
}