<?php

namespace WeiTest\App;

class DispatchBreak extends \Wei\Base
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
