<?php

namespace WeiTest\Fixtures\App\Controller;

class DispatchBreak extends \Wei\BaseController
{
    public function __construct($options = [])
    {
        parent::__construct($options);

        // Stop the app to call action method
        $this->app->preventPreviousDispatch();
    }

    public function indexAction()
    {
        return 'can not see me';
    }
}
