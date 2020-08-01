<?php

namespace WeiTest;

/**
 * @internal
 */
final class MysqlCacheTest extends CacheTestCase
{
    protected function setUp(): void
    {
        try {
            $this->object = $this->wei->mysqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        parent::setUp();
    }
}
