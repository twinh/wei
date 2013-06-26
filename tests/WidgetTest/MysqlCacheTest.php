<?php

namespace WidgetTest;

class MysqlCacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\DBAL\DriverManager'))) {
            $this->markTestSkipped('doctrine\dbal is required');
            return;
        }

        parent::setUp();

        // TODO better way
        try {
            $this->object = $this->widget->mysqlCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}