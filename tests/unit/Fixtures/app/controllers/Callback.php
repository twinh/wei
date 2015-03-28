<?php

namespace WeiTest\Fixtures\app\controllers;

class Callback extends \Wei\BaseController
{
    public function indexAction()
    {
        return 'callback';
    }

    public function before($req, $res)
    {
        $req['before'] = '1';
    }

    public function after($req, $res)
    {
        $req['after'] = '1';
    }
}