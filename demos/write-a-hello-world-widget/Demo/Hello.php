<?php

namespace Demo;

use Wei\Base;

class Hello extends Base
{
    public function __invoke($world = 'World')
    {
        return 'Hello ' . $world;
    }
}