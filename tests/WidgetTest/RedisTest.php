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
    
    public function testIncrementAndDecrement()
    {
        $redis = $this->object->getObject();
        
        // FIXME
        // Avoid segmentation fault
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        
        parent::testIncrementAndDecrement();
    }
}
