<?php

namespace WeiTest;

use Wei\NullCache;

class NullCacheTest extends TestCase
{
    public function testGet()
    {
        $this->assertNull(NullCache::get('key'));
    }

    public function testSet()
    {
        $this->assertTrue(NullCache::set('key', 'value'));
    }

    public function testDelete()
    {
        $this->assertTrue(NullCache::delete('key'));
    }

    public function testClear()
    {
        $this->assertTrue(NullCache::clear());
    }

    public function testHas()
    {
        $this->assertFalse(NullCache::has('key'));
    }

    public function testAdd()
    {
        $this->assertTrue(NullCache::add('key', 'value'));
    }

    public function testReplace()
    {
        $this->assertTrue(NullCache::replace('key', 'value'));
    }

    public function testIncr()
    {
        $this->assertTrue(NullCache::incr('key'));
    }
}
