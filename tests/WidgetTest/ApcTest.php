<?php

namespace WidgetTest;

class ApcTest extends CacheTestCase
{
    protected function setUp()
    {
        if (!extension_loaded('apc') || !ini_get('apc.enable_cli')) {
            $this->markTestSkipped('Extension "apc" is not loaded.');
        }
        parent::setUp();
    }

    public function testGet()
    {
        $apc = $this->object;

        $apc->remove('test');

        $apc->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $apc->get('test'), 'get known cache');

        $apc->remove('test');

        $this->assertFalse($apc->get('test'), 'cache has been removed');

        $apc->set('test', __METHOD__, -1);

        $this->assertFalse($apc->get('test'), 'cache is expired');
    }

    public function testObjectAsInvoker()
    {
        $apc = $this->object;

        $apc->apc(__METHOD__, true);

        $this->assertEquals(true, $apc->apc(__METHOD__));
    }

    public function testSet()
    {
        $apc = $this->object;

        $apc->remove('test2');

        $apc->set('test2', __METHOD__);

        $this->assertEquals(__METHOD__, $apc->get('test2'));
    }

    public function testAdd()
    {
        $apc = $this->object;

        $apc->remove(__METHOD__);

        $this->assertTrue($apc->add(__METHOD__, true));

        $apc->set(__METHOD__ . 'key', true);

        $this->assertFalse($apc->add(__METHOD__ . 'key', true));
    }

    public function testReplace()
    {
        $apc = $this->object;

        $apc->remove(__METHOD__);

        $this->assertFalse($apc->replace(__METHOD__, true));

        $apc->set(__METHOD__ . 'key', 'value');

        $this->assertTrue($apc->replace(__METHOD__ . 'key', true));
    }

    public function testIncr()
    {
        $apc = $this->object;

        $apc->set(__METHOD__, 1);

        $apc->incr(__METHOD__);

        $this->assertEquals($apc->get(__METHOD__), 2);

        $apc->remove(__METHOD__);

        $result = $apc->incr(__METHOD__);

        $this->assertEquals(1, $result);

        $apc->set(__METHOD__, 'string');

        $this->assertEquals(1, $apc->incr(__METHOD__));
    }

    public function testDecr()
    {
        $apc = $this->object;

        $apc->set(__METHOD__, 1);

        $apc->decr(__METHOD__);

        $this->assertEquals($apc->get(__METHOD__), 0);
    }

    public function testClear()
    {
        $apc = $this->object;

        $apc->set(__METHOD__, true);

        $apc->clear();

        $this->assertFalse($apc->get(__METHOD__), 'cache not found');
    }
}