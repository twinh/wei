<?php

namespace WeiTest;

use InvalidArgumentException;

class CacheTest extends CacheTestCase
{
    public function testDriverClassNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        new \Wei\Cache(array(
            'wei' => $this->wei,
            'driver' => 'noThisCacheDriver'
        ));
    }

    public function testNotImplemented()
    {
        $this->expectException(InvalidArgumentException::class);

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
