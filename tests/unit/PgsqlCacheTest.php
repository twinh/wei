<?php

namespace WeiTest;

/**
 * @internal
 */
final class PgsqlCacheTest extends CacheTestCase
{
    protected function setUp(): void
    {
        try {
            $this->object = $this->wei->pgsqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}
