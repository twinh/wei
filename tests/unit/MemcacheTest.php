<?php

namespace WeiTest;

class MemcacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('memcache') || !class_exists('\Memcache')) {
            $this->markTestSkipped('The memcache extension is not loaded');
        }

        parent::setUp();

        // HHVM: Unable to handle compressed values yet
        if (defined('HHVM_VERSION')) {
            $this->object->setOption('flag', 0);
        }

        if (false === @$this->object->getObject()->getStats()) {
            $this->markTestSkipped('The memcache is not running');
        }
    }

    public function testGetAndSetObject()
    {
        $cache = $this->object;
        $memcache = $cache->getObject();

        $this->assertInstanceOf('\Memcache', $memcache);

        $cache->setObject($memcache);

        $this->assertInstanceOf('\Memcache', $cache->getObject());
    }

    public function testCustomServer()
    {
        $this->wei->setConfig(array(
            'test.memcache' => array(
                'servers' => array(
                    array(
                        'host'          => 'localhost',
                        'port'          => 11211,
                        'persistent'    => true
                    )
                )
            )
        ));

        $this->assertInstanceOf('\Wei\Memcache', $this->wei->testMemcache);
    }

    public function testGetMemcacheObject()
    {
        $this->assertInstanceOf('Memcache', $this->memcache());
    }
}
