<?php

namespace WidgetTest;

class MemcacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('memcache') || !class_exists('\Memcache')) {
            $this->markTestSkipped('The memcache extension is not loaded');
        }
        
        parent::setUp();
    }
}