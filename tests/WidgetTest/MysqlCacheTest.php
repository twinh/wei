<?php

namespace WidgetTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        parent::setUp();

        try {
            $this->object = $this->widget->mysqlCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}