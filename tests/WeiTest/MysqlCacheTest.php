<?php

namespace WeiTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->wei->mysqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}
