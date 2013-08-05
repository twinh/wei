<?php

namespace WidgetTest;

class FileCacheTest extends CacheTestCase
{
    /**
     * @var \Widget\FileCache
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

        /*ob_start();

        $widget->error->option(array(
            'exit' => false,
            'error' => false,
        ));

        $this->setExpectedException('ErrorException');

        // how about linux ?
        $widget->setDirOption('./cache/|');

        $output = ob_get_contents();
        $output && ob_end_clean();

        $this->assertContains('Failed to creat directory: ', $output);*/
    }

    public function testAdd()
    {
        $widget = $this->object;

        $widget->remove(__METHOD__);

        $widget->add(__METHOD__, true);

        $this->assertEquals(true, $widget->get(__METHOD__));

        $this->assertFalse($widget->add(__METHOD__, 'the other test'), 'cache "test" is exists');

        $widget->set(__METHOD__, true, -1);

        $this->assertTrue($widget->add(__METHOD__, true), 'add cache when previous cache is expired');

        $widget->set(__METHOD__, true);

        $file = $widget->getFile(__METHOD__);

        chmod($file, 0000);

        $this->assertFalse(@$widget->add(__METHOD__, true));

        chmod($file, 0777);

        /*$file = $widget->getFile(__METHOD__);

        $handle = fopen($file, 'wb');

        flock($handle, LOCK_EX);

        sleep(10);

        $this->assertFalse($widget->add(__METHOD__, true));

        flock($handle, LOCK_UN);

        fclose($handle);*/
    }

    public function testGet()
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $widget->get('test'), 'get known cache');

        $widget->remove('test');

        $this->assertFalse($widget->get('test'), 'cache has been removed');

        $widget->set('test', __METHOD__, -1);

        $this->assertFalse($widget->get('test'), 'cache is expired');
    }

    public function testSet()
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $widget->get('test'));
    }

    public function testReplace()
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $widget->replace('test', __CLASS__);

        $this->assertEquals(__CLASS__, $widget->get('test'), 'cache replaced');

        $widget->remove('test');

        $widget->replace('test', __CLASS__);

        $this->assertFalse($widget->get('test'), 'cache not found');

        $widget->set(__METHOD__, true, -1);

        $this->assertFalse($widget->replace(__METHOD__, true), 'replace expired cache');
    }

    public function testRemove()
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $widget->remove('test');

        $this->assertEquals(null, $widget->get('test'));

        $this->assertFalse($widget->remove('test'), 'cache not found');
    }

    public function testInc()
    {
        $widget = $this->object;

        $widget->set(__METHOD__, 1);

        $widget->inc(__METHOD__);

        $this->assertEquals($widget->get(__METHOD__), 2);

        $widget->remove(__METHOD__);
        $result = $widget->inc(__METHOD__);
        $this->assertEquals(1, $result);

        $widget->set(__METHOD__, 'string');
        $widget->inc(__METHOD__);
        $this->assertEquals(1, $widget->get(__METHOD__));

        $widget->set(__METHOD__, 1, -1);
        $widget->inc(__METHOD__);
        $this->assertEquals(1, $widget->get(__METHOD__));
    }

    public function testDec()
    {
        $widget = $this->object;

        $widget->set(__METHOD__, 1);

        $widget->dec(__METHOD__);

        $this->assertEquals($widget->get(__METHOD__), 0);
    }
}
