<?php

namespace WeiTest;

class FileCacheTest extends CacheTestCase
{
    /**
     * @var \Wei\FileCache
     */
    protected $object;

    protected function tearDown()
    {
        $this->object->clear();

        rmdir($this->object->getDir());

        parent::tearDown();
    }

    public function testGetDir()
    {
        $file = $this->object;

        $newDir = $file->getDir() . DIRECTORY_SEPARATOR . 'newDir';

        $file->setDir($newDir);

        $this->assertFileExists($newDir);
    }

    public function testAdd()
    {
        $wei = $this->object;

        $wei->remove(__METHOD__);

        $wei->add(__METHOD__, true);

        $this->assertEquals(true, $wei->get(__METHOD__));

        $this->assertFalse($wei->add(__METHOD__, 'the other test'), 'cache "test" is exists');

        $wei->set(__METHOD__, true, -1);

        $this->assertTrue($wei->add(__METHOD__, true), 'add cache when previous cache is expired');

        $wei->set(__METHOD__, true);

        $file = $wei->getFile(__METHOD__);

        chmod($file, 0000);

        $this->assertFalse(@$wei->add(__METHOD__, true));

        chmod($file, 0777);

        /*$file = $wei->getFile(__METHOD__);

        $handle = fopen($file, 'wb');

        flock($handle, LOCK_EX);

        sleep(10);

        $this->assertFalse($wei->add(__METHOD__, true));

        flock($handle, LOCK_UN);

        fclose($handle);*/
    }

    public function testGet()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'), 'get known cache');

        $wei->remove('test');

        $this->assertFalse($wei->get('test'), 'cache has been removed');

        $wei->set('test', __METHOD__, -1);

        $this->assertFalse($wei->get('test'), 'cache is expired');
    }

    public function testSet()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'));
    }

    public function testReplace()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $wei->replace('test', __CLASS__);

        $this->assertEquals(__CLASS__, $wei->get('test'), 'cache replaced');

        $wei->remove('test');

        $wei->replace('test', __CLASS__);

        $this->assertFalse($wei->get('test'), 'cache not found');

        $wei->set(__METHOD__, true, -1);

        $this->assertFalse($wei->replace(__METHOD__, true), 'replace expired cache');
    }

    public function testRemove()
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $wei->remove('test');

        $this->assertEquals(null, $wei->get('test'));

        $this->assertFalse($wei->remove('test'), 'cache not found');
    }

    public function testIncr()
    {
        $wei = $this->object;

        $wei->set(__METHOD__, 1);

        $wei->incr(__METHOD__);

        $this->assertEquals($wei->get(__METHOD__), 2);

        $wei->remove(__METHOD__);
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
