<?php

namespace WidgetTest;

class MongoCacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo') || !class_exists('\Mongo')) {
            $this->markTestSkipped('The mongo extension is not loaded');
        }

        parent::setUp();
    }

    public function testGetSetObject()
    {
        $coll = $this->mongoCache->getObject();

        $this->mongoCache->setObject($coll);

        $this->assertInstanceOf('\MongoCollection', $coll);
    }
}