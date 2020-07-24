<?php

namespace WeiTest\Fixtures\App\Controller\Admin;

class Index extends \Wei\BaseController
{
    public function indexAction()
    {
        return 'admin.index';
    }

    public function viewAction()
    {
        return [
            'key' => 'value',
        ];
    }
}
