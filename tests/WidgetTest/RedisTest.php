<?php

namespace WidgetTest;

class RedisTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('redis') || !class_exists('\Redis')) {
            $this->markTestSkipped('The "redis" extension is not loaded');
        }

        parent::setUp();

        try {
            $this->object->get('test');
        } catch (\RedisException $e) {
            $this->markTestSkipped('The redis server is not running');
        }
    }

    public function testIncrementAndDecrement()
    {
        $redis = $this->object->getObject();

        // FIXME
        // Avoid segmentation fault
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);

        parent::testIncrementAndDecrement();
    }

    public function testGetAndSetObject()
    {
        $cache = $this->object;
        $redis = $cache->getObject();

        $this->assertInstanceOf('\Redis', $redis);

        $cache->setObject($redis);

        $this->assertInstanceOf('\Redis', $cache->getObject());
    }
}
