<?php

namespace WeiTest\App\Admin;

class Index extends \Wei\Base
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
