<?php

namespace WeiTest\Fixtures;

use Wei\Base;

class User extends Base
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
