<?php

namespace WeiTest;

class RedisTest extends TestCase
{
    /**
     * @var \Wei\BaseCache
     */
    protected $object;

    public function setUp()
    {
        if (!extension_loaded('redis') || !class_exists('\Redis')) {
            $this->markTestSkipped('The "redis" extension is not loaded');
        }

        parent::setUp();

        $this->object->setNamespace('test-');

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

    /**
     * @dataProvider providerForGetterAndSetter
     */
    public function testGetterAndSetter($value, $key)
    {
        $cache = $this->object;

        $cache->remove($key);
        $this->assertFalse($cache->get($key));

        $this->assertFalse($cache->replace($key, $value));
        $this->assertTrue($cache->add($key, $value));

        $cache->set($key, $value);
        $this->assertEquals($value, $cache->get($key));

        $this->assertFalse($cache->add($key, $value));

        // Replace with the same value for testing MySQL cache
        $this->assertTrue($cache->replace($key, $value));

        $this->assertTrue($cache->replace($key, uniqid()));
    }

    public function providerForGetterAndSetter()
    {
        $obj = new \stdClass;

        return array(
            array(array(),  'array'),
            array(true,     'bool'),
            array(1.2,      'float'),
            array(1,        'int'),
            array(1,        'integer'),
            array(null,     'null'),
            array('1',      'numeric'),
            array($obj,     'object'),
        );
    }

    public function tearDown()
    {
        /** @var \Redis $redis */
        $redis = $this->object->getObject();

        var_dump('Redis last error', $redis->getLastError());

        return parent::tearDown();
    }
}
