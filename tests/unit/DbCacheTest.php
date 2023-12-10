<?php

namespace WeiTest;

/**
 * @internal
 */
final class DbCacheTest extends CacheTestCase
{
    public static function tearDownAfterClass(): void
    {
        @unlink('cache.sqlite');
        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        try {
            $this->object = $this->wei->dbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        parent::setUp();
    }

    public function testExpire()
    {
        $cache = $this->object;
        $key = __FUNCTION__;

        $result = $cache->set($key, true, -1);
        $this->assertTrue($result);

        // sleep(2);

        $result = $cache->has($key);
        $this->assertFalse($result);

        $result = $cache->set($key, true, -1);
        $this->assertTrue($result);

        // sleep(2);

        $result = $cache->get($key);
        $this->assertNull($result);
    }
}
