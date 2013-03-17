<?php

namespace WidgetTest;

class MemcachedTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('memcached') || !class_exists('\Memcached')) {
            $this->markTestSkipped('The memcache extension is not loaded');
        }
        
        parent::setUp();
    }
}
