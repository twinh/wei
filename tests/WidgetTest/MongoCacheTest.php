<?php

namespace WidgetTest;

class MongoCacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo') || !class_exists('\Mongo')) {
            $this->markTestSkipped('The mongo extension is not loaded');
        }

        if (!method_exists('MongoCollection', 'findAndModify')) {
            $this->markTestSkipped('Required mongo version equals or greater than 1.3.0');
        }

        try {
            parent::setUp();
        } catch (\MongoConnectionException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testGetSetObject()
    {
        $coll = $this->mongoCache->getObject();

        $this->mongoCache->setObject($coll);

        $this->assertInstanceOf('\MongoCollection', $coll);
    }
}