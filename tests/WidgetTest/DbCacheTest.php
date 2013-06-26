<?php

namespace WidgetTest;

class DbCacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\DBAL\DriverManager'))) {
            $this->markTestSkipped('doctrine\dbal is required');
            return;
        }
        
        parent::setUp();
    }
    
    public static function tearDownAfterClass()
    {
        @unlink('cache.sqlite');
        parent::tearDownAfterClass();
    }
}