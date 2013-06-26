<?php

namespace WidgetTest\Fixtures;

/** @Entity @Table(name="user") */
class UserEntity 
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=50) */
    private $name;

    /** @Column(type="string", length=256) */
    protected $email;
        
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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
