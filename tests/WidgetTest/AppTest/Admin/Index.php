<?php

namespace WidgetTest\AppTest\Admin;

class Index extends \Widget\AbstractWidget
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