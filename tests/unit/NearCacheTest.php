<?php

namespace WeiTest;

use Wei\NearCache;

/**
 * @internal
 * @property NearCache $object
 */
final class NearCacheTest extends CacheTestCase
{
    protected function tearDown(): void
    {
        $this->object->clear();

        parent::tearDown();
    }

    public function testGetFromFront()
    {
        $time = time();

        $this->object->getFront()->set(__FUNCTION__, $time);
        $this->object->getBack()->delete(__FUNCTION__);

        $this->assertSame($time, $this->object->get(__FUNCTION__));
    }

    public function testGetFromBack()
    {
        $time = time();

        $this->object->getFront()->delete(__FUNCTION__);
        $this->object->getBack()->set(__FUNCTION__, $time);

        $this->assertSame($time, $this->object->get(__FUNCTION__));

        $this->assertSame($time, $this->object->getFront()->get(__FUNCTION__));
    }

    public function testSetBoth()
    {
        $time = time();
        $this->object->set(__FUNCTION__, $time);

        $front = $this->object->getFront();
        $back = $this->object->getFront();

        $this->assertSame($time, $front->get(__FUNCTION__));
        $this->assertSame($time, $back->get(__FUNCTION__));
    }
}
