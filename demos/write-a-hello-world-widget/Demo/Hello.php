<?php

namespace Demo;

use Widget\Base;

class Hello extends Base
{
    public function __invoke($world = 'World')
    {
        return 'Hello ' . $world;
    }
}