<?php

namespace controllers;

class Index extends Base
{
    public function index()
    {
        return 'Welcome!';
    }

    public function hello()
    {
        return 'Hello World';
    }
}