<?php

namespace WidgetTest;

class DbCacheTest extends CacheTestCase
{
    public static function tearDownAfterClass()
    {
        @unlink('cache.sqlite');
        parent::tearDownAfterClass();
    }
}