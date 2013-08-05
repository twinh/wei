<?php

namespace WidgetTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->widget->mysqlCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}