<?php

namespace WeiTest;

/**
 * @internal
 */
class FileCacheTest extends CacheTestCase
{
    /**
     * @var \Wei\FileCache
     */
    protected $object;

    protected function tearDown(): void
    {
        $this->object->clear();

        rmdir($this->object->getDir());

        parent::tearDown();
    }

    public function testGetDir()
    {
        $file = $this->object;

        $newDir = $file->getDir() . \DIRECTORY_SEPARATOR . 'newDir';

        $file->setDir($newDir);

        $this->assertFileExists($newDir);
    }

    public function testAdd()
    {
        $wei = $this->object;

        $wei->delete(__METHOD__);

        $wei->add(__METHOD__, true);

        $this->assertTrue($wei->get(__METHOD__));

        $this->assertFalse($wei->add(__METHOD__, 'the other test'), 'cache "test" is exists');

        $wei->set(__METHOD__, true, -1);

        $this->assertTrue($wei->add(__METHOD__, true), 'add cache when previous cache is expired');

        $wei->set(__METHOD__, true);

        $file = $wei->getFile(__METHOD__);

        chmod($file, 0000);

        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
        $this->assertFalse(@$wei->add(__METHOD__, true));

        chmod($file, 0777);
    }

    public function testGet()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'), 'get known cache');

        $wei->delete('test');

        $this->assertNull($wei->get('test'), 'cache has been removed');

        $wei->set('test', __METHOD__, -1);

        $this->assertNull($wei->get('test'), 'cache is expired');
    }

    public function testSet()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'));
    }

    public function testReplace()
    {
        $cache = $this->object;

        $cache->set('test', __METHOD__);

        $cache->replace('test', __CLASS__);

        $this->assertEquals(__CLASS__, $cache->get('test'), 'cache replaced');

        $cache->delete('test');

        $cache->replace('test', __CLASS__);

        $this->assertNull($cache->get('test'), 'cache not found');

        $cache->set(__METHOD__, true, -1);

        $this->assertFalse($cache->replace(__METHOD__, true), 'replace expired cache');
    }

    public function testRemove()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $wei->delete('test');

        $this->assertNull($wei->get('test'));

        $this->assertFalse($wei->delete('test'), 'cache not found');
    }

    public function testIncr()
    {
        $wei = $this->object;

        $wei->set(__METHOD__, 1);

        $wei->incr(__METHOD__);

        $this->assertEquals($wei->get(__METHOD__), 2);

        $wei->delete(__METHOD__);
        $result = $wei->incr(__METHOD__);
        $this->assertEquals(1, $result);

        $wei->set(__METHOD__, 'string');
        $wei->incr(__METHOD__);
        $this->assertEquals(1, $wei->get(__METHOD__));

        $wei->set(__METHOD__, 1, -1);
        $wei->incr(__METHOD__);
        $this->assertEquals(1, $wei->get(__METHOD__));
    }

    public function testDecr()
    {
        $wei = $this->object;

        $wei->set(__METHOD__, 1);

        $wei->decr(__METHOD__);

        $this->assertEquals($wei->get(__METHOD__), 0);
    }
}
