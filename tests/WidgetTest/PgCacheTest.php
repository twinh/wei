<?php

namespace WidgetTest;

class PgCacheTest extends CacheTestCase
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
            $this->object = $this->widget->pgCache;
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}