<?php

namespace WeiTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp(): void
    {
        try {
            $this->object = $this->wei->mysqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}
