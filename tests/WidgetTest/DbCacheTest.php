<?php

namespace WidgetTest;

class DbCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->widget->dbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public static function tearDownAfterClass()
    {
        @unlink('cache.sqlite');
        parent::tearDownAfterClass();
    }
}