<?php

namespace WeiTest\Fixtures\app\controllers;

class DispatchBreak extends \Wei\BaseController
{
    public function __construct($options = array())
    {
        parent::__construct($options);

        // Stop the app to call action method
        $this->app->preventPreviousDispatch();
    }

    public function index()
    {
        return 'can not see me';
    }
}
