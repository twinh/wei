<?php

namespace WidgetTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        try {
            $this->object = $this->widget->mysqlDbCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}