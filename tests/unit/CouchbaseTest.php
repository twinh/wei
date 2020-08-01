<?php

namespace WeiTest;

/**
 * @internal
 */
final class CouchbaseTest extends CacheTestCase
{
    /** @phpstan-ignore-next-line Missing call to parent::setUp() method. */
    protected function setUp(): void
    {
        $mock = false;

        if (!extension_loaded('couchbase') || !class_exists('\Couchbase')) {
            $mock = true;
        } else {
            parent::setUp();
            if ($error = error_get_last()) {
                $mock = true;
            }
        }

        if (!$mock) {
            return;
        }

        // Mock Couchbase object for most of platforms have not installed Couchbase yet.
        $couchbase = $this->getMockBuilder('Couchbase')
            ->disableOriginalConstructor()
            ->getMock();

        $this->wei->setConfig('couchbase', [
            'object' => $couchbase,
        ]);

        $cache = $this->wei->newInstance('arrayCache');
        $object = $this->object = $this->wei->couchbase;

        $couchbase->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($cache) {
                return $cache->get($key);
            });

        $couchbase->expects($this->any())
            ->method('set')
            ->willReturnCallback(function ($key, $value, $expire = 0) use ($cache) {
                return $cache->set($key, $value, $expire);
            });

        $couchbase->expects($this->any())
            ->method('delete')
            ->willReturnCallback(function ($key) use ($cache) {
                return $cache->remove($key);
            });

        $couchbase->expects($this->any())
            ->method('add')
            ->willReturnCallback(function ($key, $value, $expire = 0) use ($cache) {
                return $cache->add($key, $value, $expire);
            });

        $couchbase->expects($this->any())
            ->method('replace')
            ->willReturnCallback(function ($key, $value, $expire = 0) use ($cache) {
                return $cache->replace($key, $value, $expire);
            });

        $couchbase->expects($this->any())
            ->method('inc')
            ->willReturnCallback(function ($key, $offset) use ($cache) {
                return $cache->incr($key, $offset);
            });

        $couchbase->expects($this->any())
            ->method('dec')
            ->willReturnCallback(function ($key, $offset) use ($cache) {
                return $cache->decr($key, $offset);
            });

        $couchbase->expects($this->any())
            ->method('flush')
            ->willReturnCallback(function () use ($cache) {
                return $cache->clear();
            });

        $couchbase->expects($this->any())
            ->method('setMulti')
            ->willReturnCallback(function ($items, $expire = 0) use ($cache, $object) {
                $cache->setNamespace($object->getNamespace());
                $result = $cache->setMulti($items, $expire);
                $cache->setNamespace('');
                return $result;
            });

        $couchbase->expects($this->any())
            ->method('getMulti')
            ->willReturnCallback(function ($items) use ($cache, $object) {
                $cache->setNamespace($object->getNamespace());
                $result = $cache->getMulti($items);
                $cache->setNamespace('');
                return $result;
            });
    }

    public function testGetObject()
    {
        $this->assertInstanceOf('Couchbase', $this->object->getObject());
    }
}
