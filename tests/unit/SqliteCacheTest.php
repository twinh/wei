<?php

namespace WeiTest;

/**
 * @internal
 */
final class SqliteCacheTest extends CacheTestCase
{
    protected function setUp(): void
    {
        try {
            $this->object = $this->wei->sqliteDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        parent::setUp();
    }
}
