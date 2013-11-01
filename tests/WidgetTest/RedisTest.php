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

    public function testIncrAndDecr()
    {
        $redis = $this->object->getObject();
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);

        parent::testIncrAndDecr();
    }

    public function testKeyPrefix()
    {
        $redis = $this->object->getObject();
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);

        parent::testKeyPrefix();
    }

    public function testGetAndSetObject()
    {
        $cache = $this->object;
        $redis = $cache->getObject();

        $this->assertInstanceOf('\Redis', $redis);

        $cache->setObject($redis);

        $this->assertInstanceOf('\Redis', $cache->getObject());
    }

    public function testGetRedisObject()
    {
        $this->assertInstanceOf('\Redis', $this->redis());
    }

    public function testConnectWithExistsObject()
    {
        $this->assertTrue($this->object->connect());
    }
}
