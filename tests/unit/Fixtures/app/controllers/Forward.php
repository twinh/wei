<?php

namespace WeiTest\Fixtures\app\controllers;

class Forward extends \Wei\Base
{
    public function index()
    {
        return 'index';
    }

    public function target()
    {
        return 'target';
    }
}
