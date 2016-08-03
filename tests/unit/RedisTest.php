<?php

namespace WeiTest;

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

        /** @var \Redis $redis */
        $redis = $this->object->getObject();
        $error = $redis->getLastError();
        if ($error) {
            $this->markTestSkipped('Redis error: ' . $error);
        }

        $result = $redis->set('a', 'b');
        if (!$result) {
            $this->markTestSkipped('Redis set error: ' . $redis->getLastError());
        }

        $result = $redis->get('a');
        if ($result !== 'b') {
            $this->markTestSkipped('Redis get error: ' . $redis->getLastError());
        }
    }

    public function tearDown()
    {
        /** @var \Redis $redis */
        $redis = $this->object->getObject();

        var_dump('Redis last error', $redis->getLastError());

        return parent::tearDown();
    }

    public function testIncrAndDecr()
    {
        $redis = $this->object->getObject();
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);

        parent::testIncrAndDecr();
    }

    public function testPrefix()
    {
        $redis = $this->object->getObject();
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);

        parent::testPrefix();
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
