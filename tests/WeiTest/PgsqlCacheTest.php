<?php

namespace WeiTest;

class PgsqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->wei->pgsqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}
