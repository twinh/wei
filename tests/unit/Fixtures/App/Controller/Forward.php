<?php

namespace WeiTest\Fixtures\App\Controller;

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

    public function paramsAction($req)
    {
        return $req['param'];
    }
}
