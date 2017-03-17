<?php

namespace WeiTest\Fixtures\app\controllers\admin;

class Index extends \Wei\BaseController
{
    public function indexAction()
    {
        return 'admin.index';
    }

    public function viewAction()
    {
        return array(
            'key' => 'value'
        );
    }
}
