<?php

namespace WidgetTest;

class DbTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\DBAL\DriverManager'))) {
            $this->markTestSkipped('doctrine\dbal is required');
        }
        
        parent::setUp();
    }
    
    public function testWidget()
    {
        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $this->db());
    }
}