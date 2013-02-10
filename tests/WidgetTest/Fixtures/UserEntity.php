<?php

namespace WidgetTest\Fixtures;

class UserEntity 
{
    protected $email;
    
    public function __construct($email) 
    {
        $this->setEmail($email);
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }
}
