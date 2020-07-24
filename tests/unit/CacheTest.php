<?php

namespace WeiTest;

use InvalidArgumentException;

/**
 * @internal
 */
final class CacheTest extends CacheTestCase
{
    public function testDriverClassNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        new \Wei\Cache([
            'wei' => $this->wei,
            'driver' => 'noThisCacheDriver',
        ]);
    }

    public function testNotImplemented()
    {
        $this->expectException(InvalidArgumentException::class);

        new \Wei\Cache([
            'wei' => $this->wei,
            'driver' => 'request',
        ]);
    }

    public function testCacheDriver()
    {
        $this->cache->setDriver('phpFileCache');

        $this->assertEquals('phpFileCache', $this->cache->getDriver());
    }
}
