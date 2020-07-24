<?php

namespace WeiTest;

/**
 * @internal
 */
final class MemcacheTest extends CacheTestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('memcache') || !class_exists('\Memcache')) {
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
        $memcache = $cache->getObject();

        $this->assertInstanceOf('\Memcache', $memcache);

        $cache->setObject($memcache);

        $this->assertInstanceOf('\Memcache', $cache->getObject());
    }

    public function testCustomServer()
    {
        $this->wei->setConfig([
            'test.memcache' => [
                'servers' => [
                    [
                        'host' => 'localhost',
                        'port' => 11211,
                        'persistent' => false,
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf('\Wei\Memcache', $this->wei->testMemcache);
    }

    public function testGetMemcacheObject()
    {
        $this->assertInstanceOf('Memcache', $this->memcache());
    }
}
