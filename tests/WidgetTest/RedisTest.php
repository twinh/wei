<?php

namespace WidgetTest;

class RedisTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('redis') || !class_exists('\Redis')) {
            $this->markTestSkipped('The memcache extension is not loaded');
        }
        
        parent::setUp();
    }
}
