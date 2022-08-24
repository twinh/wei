<?php

namespace WeiTest;

/**
 * @internal
 */
final class ApcuTest extends CacheTestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('apcu')) {
            $this->markTestSkipped('Extension "apcu" is not loaded.');
        }
        if (!ini_get('apc.enable_cli')) {
            $this->markTestSkipped('APC is not enabled in CLI');
        }
        parent::setUp();
    }

    public function testGet()
    {
        $apcu = $this->object;

        $apcu->delete('test');

        $apcu->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $apcu->get('test'), 'get known cache');

        $apcu->delete('test');

        $this->assertNull($apcu->get('test'), 'cache has been removed');

        $apcu->set('test', __METHOD__, -1);

        $this->assertNull($apcu->get('test'), 'cache is expired');
    }

    public function testObjectAsInvoker()
    {
        $apcu = $this->object;

        $apcu->apcu(__METHOD__, true);

        $this->assertTrue($apcu->apcu(__METHOD__));
    }

    public function testSet()
    {
        $apcu = $this->object;

        $apcu->delete('test2');

        $apcu->set('test2', __METHOD__);

        $this->assertEquals(__METHOD__, $apcu->get('test2'));
    }

    public function testAdd()
    {
        $apcu = $this->object;

        $apcu->delete(__METHOD__);

        $this->assertTrue($apcu->add(__METHOD__, true));

        $apcu->set(__METHOD__ . 'key', true);

        $this->assertFalse($apcu->add(__METHOD__ . 'key', true));
    }

    public function testReplace()
    {
        $apcu = $this->object;

        $apcu->delete(__METHOD__);

        $this->assertFalse($apcu->replace(__METHOD__, true));

        $apcu->set(__METHOD__ . 'key', 'value');

        $this->assertTrue($apcu->replace(__METHOD__ . 'key', true));
    }

    public function testIncr()
    {
        $apcu = $this->object;

        $apcu->set(__METHOD__, 1);

        $apcu->incr(__METHOD__);

        $this->assertEquals($apcu->get(__METHOD__), 2);

        $apcu->delete(__METHOD__);

        $result = $apcu->incr(__METHOD__);

        $this->assertEquals(1, $result);

        $apcu->set(__METHOD__, 'string');

        $this->assertEquals(1, $apcu->incr(__METHOD__));
    }

    public function testDecr()
    {
        $apcu = $this->object;

        $apcu->set(__METHOD__, 1);

        $apcu->decr(__METHOD__);

        $this->assertEquals($apcu->get(__METHOD__), 0);
    }

    public function testClear()
    {
        $apcu = $this->object;

        $apcu->set(__METHOD__, true);

        $apcu->clear();

        $this->assertNull($apcu->get(__METHOD__), 'cache not found');
    }
}
