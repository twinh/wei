<?php

namespace WeiTest;

class CouchbaseTest extends CacheTestCase
{
    public function setUp()
    {
        $mock = false;

        if (!extension_loaded('couchbase') || !class_exists('\Couchbase')) {
            $mock = true;
            //$this->markTestSkipped('The "couchbase" extension is not loaded');
        } else {
            @parent::setUp();
            if ($error = error_get_last()) {
                $mock = true;
                //$this->markTestSkipped($error['message']);
            }
        }

        if (!$mock) {
            return;
        }

        // Mock Couchbase object for most of platforms have not installed Couchbase yet.
        $couchbase = $this->getMockBuilder('Couchbase')
            ->disableOriginalConstructor()
            ->getMock();

        $this->wei->setConfig('couchbase', array(
            'object' => $couchbase
        ));

        $cache = $this->wei->newInstance('arrayCache');
        $object = $this->object = $this->wei->couchbase;

        $couchbase->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key) use($cache) {
                return $cache->get($key);
            }));

        $couchbase->expects($this->any())
            ->method('set')
            ->will($this->returnCallback(function($key, $value, $expire = 0) use($cache) {
                return $cache->set($key, $value, $expire);
            }));

        $couchbase->expects($this->any())
            ->method('delete')
            ->will($this->returnCallback(function($key) use($cache) {
                return $cache->remove($key);
            }));

        $couchbase->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function($key, $value, $expire = 0) use($cache) {
                return $cache->add($key, $value, $expire);
            }));

        $couchbase->expects($this->any())
            ->method('replace')
            ->will($this->returnCallback(function($key, $value, $expire = 0) use($cache) {
                return $cache->replace($key, $value, $expire);
            }));

        $couchbase->expects($this->any())
            ->method('inc')
            ->will($this->returnCallback(function($key, $offset) use($cache) {
                return $cache->incr($key, $offset);
            }));

        $couchbase->expects($this->any())
            ->method('dec')
            ->will($this->returnCallback(function($key, $offset) use($cache) {
                return $cache->decr($key, $offset);
            }));

        $couchbase->expects($this->any())
            ->method('flush')
            ->will($this->returnCallback(function() use($cache) {
                return $cache->clear();
            }));

        $couchbase->expects($this->any())
            ->method('setMulti')
            ->will($this->returnCallback(function($items, $expire = 0) use($cache, $object) {
                $cache->setPrefix($object->getPrefix());
                $result = $cache->setMulti($items, $expire);
                $cache->setPrefix('');
                return $result;
            }));

        $couchbase->expects($this->any())
            ->method('getMulti')
            ->will($this->returnCallback(function($items) use($cache, $object) {
                $cache->setPrefix($object->getPrefix());
                $result = $cache->getMulti($items);
                $cache->setPrefix('');
                return $result;
            }));
    }

    public function testGetObject()
    {
        $this->assertInstanceOf('Couchbase', $this->object->getObject());
    }
}
