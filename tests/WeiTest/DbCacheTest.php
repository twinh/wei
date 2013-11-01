<?php

namespace WeiTest;

class DbCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->wei->dbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public static function tearDownAfterClass()
    {
        @unlink('cache.sqlite');
        parent::tearDownAfterClass();
    }

    public function testExpire()
    {
        $cache = $this->object;
        $key = __FUNCTION__;

        $result = $cache->set($key, true, -1);
        $this->assertTrue($result);

        //sleep(2);

        $result = $cache->exists($key);
        $this->assertFalse($result);

        $result = $cache->set($key, true, -1);
        $this->assertTrue($result);

        //sleep(2);

        $result = $cache->get($key);
        $this->assertFalse($result);
    }

}
