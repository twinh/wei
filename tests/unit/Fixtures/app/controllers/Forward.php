<?php

namespace WeiTest\Fixtures\app\controllers;

class Forward extends \Wei\BaseController
{
    public function indexAction()
    {
        return 'index';
    }

    public function targetAction()
    {
        return 'target';
    }
}
