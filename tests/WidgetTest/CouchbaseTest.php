<?php

namespace WidgetTest;

class CouchbaseTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('couchbase') || !class_exists('\Couchbase')) {
            $this->markTestSkipped('The "couchbase" extension is not loaded');
        }

        @parent::setUp();

        if ($error = error_get_last()) {
            $this->markTestSkipped($error['message']);
        }
    }
}