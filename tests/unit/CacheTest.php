<?php

namespace WeiTest;

class CacheTest extends CacheTestCase
{
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
