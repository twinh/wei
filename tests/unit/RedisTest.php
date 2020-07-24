<?php

namespace WeiTest;

/**
 * @internal
 */
final class RedisTest extends CacheTestCase
{
    protected function setUp(): void
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

    public function testAddWithExpire()
    {
        $cache = $this->object;

        $cache->add(__METHOD__, __METHOD__, 1);

        $this->assertEquals(__METHOD__, $cache->get(__METHOD__));

        // sleep for 1.1s
        usleep(1100000);

        $this->assertFalse($cache->get(__METHOD__));
    }
}
