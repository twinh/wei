<?php

namespace WeiTest\Fixtures;

class Instance
{
    public $arg1;
    public $arg2;
    public $arg3;
    public $arg4;

    public function __construct($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
        $this->arg4 = $arg4;
    }
}
