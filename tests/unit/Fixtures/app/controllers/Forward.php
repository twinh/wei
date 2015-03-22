<?php

namespace WeiTest\Fixtures\app\controllers;

class Forward extends \Wei\BaseController
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
