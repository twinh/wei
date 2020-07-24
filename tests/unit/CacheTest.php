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
        $driver = $this->cache->getDriver();

        $this->cache->setDriver('phpFileCache');

        $this->assertEquals('phpFileCache', $this->cache->getDriver());

        $this->cache->setDriver($driver);
    }
}
