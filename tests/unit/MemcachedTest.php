<?php

namespace WeiTest;

class MemcachedTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('memcached') || !class_exists('\Memcached')) {
            $this->markTestSkipped('The memcache extension is not loaded');
        }

        parent::setUp();

        if (false === @$this->object->getObject()->getStats()) {
            $this->markTestSkipped('The memcache is not running');
        }
    }

    public function testGetAndSetObject()
    {
        $cache = $this->object;
        $memcached = $cache->getObject();

        $this->assertInstanceOf('\Memcached', $memcached);

        $cache->setObject($memcached);

        $this->assertInstanceOf('\Memcached', $cache->getObject());
    }

    public function testCustomServer()
    {
        $this->wei->setConfig(array(
            'test.memcached' => array(
                'servers' => array(
                    array(
                        'host'          => 'localhost',
                        'port'          => 11211,
                        'persistent'    => false
                    )
                )
            )
        ));

        $this->assertInstanceOf('\Wei\Memcached', $this->wei->testMemcached);
    }

    public function testGetMemcachedObject()
    {
        $this->assertInstanceOf('Memcached', $this->memcached());
    }
}
