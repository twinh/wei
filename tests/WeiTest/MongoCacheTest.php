<?php

namespace WeiTest;

class MongoCacheTest extends CacheTestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo') || !class_exists('MongoClient')) {
            $this->markTestSkipped('The mongo extension is not loaded');
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
