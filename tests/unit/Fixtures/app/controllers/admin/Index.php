<?php

namespace WeiTest\Fixtures\app\controllers\admin;

class Index extends \Wei\BaseController
{
    public function index()
    {
        return 'admin.index';
    }

    public function view()
    {
        return array(
            'key' => 'value'
        );
    }
}
