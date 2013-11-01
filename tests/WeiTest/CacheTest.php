<?php

namespace WeiTest;

class CacheTest extends CacheTestCase
{
    protected function setUp()
    {
        // FIXME
        if (!extension_loaded('apc') || !ini_get('apc.enable_cli')) {
            $this->markTestSkipped('Extension "apc" is not loaded.');
        }
        parent::setUp();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDriverClassNotFound()
    {
        new \Wei\Cache(array(
            'wei' => $this->wei,
            'driver' => 'noThisCacheDriver'
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotImplemented()
    {
        new \Wei\Cache(array(
            'wei' => $this->wei,
            'driver' => 'request'
        ));
    }

    public function testCacheDriver()
    {
        $this->cache->setDriver('redis');

        $this->assertEquals('redis', $this->cache->getDriver('redis'));
    }
}
