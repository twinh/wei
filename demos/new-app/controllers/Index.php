<?php

namespace controllers;

class Index extends Base
{
    public function indexAction()
    {
        return 'Welcome!';
    }

    public function viewAction()
    {
        $name = 'Hello World';
        return get_defined_vars();
    }
}
