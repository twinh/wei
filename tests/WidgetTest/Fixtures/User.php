<?php

namespace WidgetTest\Fixtures;

use Widget\AbstractWidget;

class User extends AbstractWidget
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