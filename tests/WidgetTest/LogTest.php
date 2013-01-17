<?php

namespace WidgetTest;

class LogTest extends TestCase
{
    protected function tearDown()
    {
        $this->object->clean();
        
        $dir = $this->object->option('dir');
        if (is_dir($dir)) {
            rmdir($dir);
        }
        
        parent::tearDown();
    }

    public function testLog()
    {
        $logger = $this->object;

        $logger->option('level', 'debug');

        $logger->debug(__METHOD__);

        $file = $logger->getFile();

        $this->assertContains(__METHOD__, file_get_contents($file));

        // clean all file in log diretory
        $logger->clean();

        $logger->option('level', 'info');

        $logger->debug(__METHOD__);
        
        $this->assertFileNotExists($file);
    }

    public function testGetFileOption()
    {
        $logger = $this->object;
        
        $logger->option('level', 'debug');

        $logger->option('fileDetected', false);

        $logger->debug(__METHOD__);

        $oldFile = $logger->getFile();

        // Make it always create new file
        $logger->option('fileSize', 1);

        // Create the second file
        $logger->debug(__METHOD__);

        $logger->option('fileDetected', false);

        // Create the thrid file
        $logger->debug(__METHOD__);

        $logger->option('fileDetected', false);

        // Create the fouth file
        $logger->debug(__METHOD__);

        $logger->option('fileDetected', false);

        $newFile = $logger->getFile();

        $this->assertNotEquals($oldFile, $newFile);
    }
    
    public function testAllLevel()
    {
        $logger = $this->object;
        
        $logger->option('level', 'debug');

        $logger->debug(__METHOD__);

        $file = $logger->option('file');

        $this->assertContains(__METHOD__, file_get_contents($file));
    }

    public function testSetFileOption()
    {
        $logger = $this->object;
        
        $logger->option('level', 'debug');
        
        $newFile = dirname($logger->getFile()) . DIRECTORY_SEPARATOR . mt_rand(0, 1000);

        $logger->setFile($newFile);

        $logger->debug(__METHOD__);

        $this->assertFileExists($newFile);
    }

    public function testSetDir()
    {
        $logger = $this->object;
        
        $file = $logger->getFile();

        $oldDir = $logger->option('dir');

        $newDir = realpath($oldDir) . DIRECTORY_SEPARATOR . 'subdir';

        $logger->setDir($newDir);

        $file = $logger->option('file');

        $this->assertEquals($newDir, dirname($file));

        rmdir($newDir);
    }

    public function testSetFileFormatOption()
    {
        $logger = $this->object;

        $format = $logger->option('fileFormat');

        $file = $logger->option('file');

        $logger->option('fileFormat', 'newfile.log');

        $logger->debug(__METHOD__);

        $file = dirname($file) . DIRECTORY_SEPARATOR . 'newfile.log';

        $this->assertFileExists($file);
    }

    public function testSetFileSizeOption()
    {
        $logger = $this->object;

        $logger->option('file', $logger->option('file'));

        $logger->debug(__METHOD__);

        $oldFile = $logger->option('file');

        $size = $logger->option('fileSize');

        // always create new file
        $logger->option('fileSize', 1);

        $logger->debug(__METHOD__);

        $newFile = $logger->option('file');

        $this->assertNotEquals($oldFile, $newFile);
    }
}
