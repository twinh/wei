<?php

namespace WidgetTest;

class PgsqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->widget->pgsqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}