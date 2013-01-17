<?php

namespace WidgetTest;

class LogTest extends TestCase
{
    protected function setUp() {
        $this->widget->config('log', array(
            'dir' => sys_get_temp_dir(),
        ));
        parent::setUp();
        $this->object->clean();
    }

    protected function tearDown()
    {
        $this->object->clean();
        parent::tearDown();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        
//        $log = Qwin::getInstance()->log;
//
//        $log->clean();
//
//        $log->option('save', array(
//            __CLASS__, 'notSave',
//        ));
//
//        rmdir($log->option('dir'));
    }

    public static function notSave()
    {

    }

    public function testLog() {
        $widget = $this->object;

        $widget->option('level', 'debug');

        $widget->debug(__METHOD__);

        $file = $widget->option('file');

        $this->assertContains(__METHOD__, file_get_contents($file));

        // clean all file in log diretory
        $widget->clean();

        $widget->option('level', 'info');

        $widget->debug(__METHOD__);
        
        $this->assertFileNotExists($file);
    }

    public function testGetFileOption()
    {
        $widget = $this->object;

        $widget->option('fileDetected', false);

        $widget->debug(__METHOD__);

        $oldFile = $widget->option('file');

        $size = $widget->option('fileSize');

        // always create new file
        $widget->option('fileSize', 1);

        // create the second file
        $widget->debug(__METHOD__);

        $widget->option('fileDetected', false);

        // create the thrid file
        $widget->debug(__METHOD__);

        $widget->option('fileDetected', false);

        // create the fouth file
        $widget->debug(__METHOD__);

        $widget->option('fileDetected', false);

        //$newFile = $widget->option('file');

        //$this->assertNotEquals($oldFile, $newFile);
    }
    
    public function testAllLevel()
    {
        $widget = $this->object;

        $widget->option('level', 'debug');

        $widget->debug(__METHOD__);

        $file = $widget->option('file');

        $this->assertContains(__METHOD__, file_get_contents($file));
    }

    public function testSetFileOption()
    {
        $widget = $this->object;

        $dir = dirname($widget->option('file')) . DIRECTORY_SEPARATOR . __FUNCTION__;
        $file = $dir . DIRECTORY_SEPARATOR . __LINE__;

        // clean file and directory
        if (is_file($file)) {
            unlink($file);
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }

        $widget->option('file', $file);

        $widget->debug(__METHOD__);

        $this->assertFileExists($file);

        $widget->option('fileDetected', false);

        $widget->clean();
        // clean again
//        if (is_file($file)) {
//            unlink($file);
//        }
//        if (is_dir($dir)) {
//            rmdir($dir);
//        }
    }

    public function testSetFileDirOption()
    {
        $widget = $this->object;

        $oldDir = $widget->option('dir');

        $newDir = realpath($oldDir) . DIRECTORY_SEPARATOR . 'subdir';

        $widget->option('dir', $newDir);

        $file = $widget->option('file');

        $this->assertEquals($newDir, dirname($file));

        rmdir($newDir);

        $widget->option('dir', $oldDir);
    }

    public function testSetFileFormatOption()
    {
        $widget = $this->object;

        $format = $widget->option('fileFormat');

        $file = $widget->option('file');

        $widget->option('fileFormat', 'newfile.log');

        $widget->debug(__METHOD__);

        $file = dirname($file) . DIRECTORY_SEPARATOR . 'newfile.log';

        $this->assertFileExists($file);
    }

    public function testSetFileSizeOption()
    {
        $widget = $this->object;

        $widget->option('file', $widget->option('file'));

        $widget->debug(__METHOD__);

        $oldFile = $widget->option('file');

        $size = $widget->option('fileSize');

        // always create new file
        $widget->option('fileSize', 1);

        $widget->debug(__METHOD__);

        $newFile = $widget->option('file');

        $this->assertNotEquals($oldFile, $newFile);
    }
}
