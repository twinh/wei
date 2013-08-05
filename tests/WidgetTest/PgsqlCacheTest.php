<?php

namespace WidgetTest;

class PgsqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->widget->pgsqlCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}