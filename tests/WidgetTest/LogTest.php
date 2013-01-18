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

        $logger->addDebug(__METHOD__);

        $file = $logger->getFile();

        $this->assertContains(__METHOD__, file_get_contents($file));

        // clean all file in log diretory
        $logger->clean();

        $logger->option('level', 'info');

        $logger->addDebug(__METHOD__);
        
        $this->assertFileNotExists($file);
    }

    public function testGetFileOption()
    {
        $logger = $this->object;
        
        $logger->option('level', 'debug');

        $logger->option('fileDetected', false);

        $logger->addDebug(__METHOD__);

        $oldFile = $logger->getFile();

        // Make it always create new file
        $logger->option('fileSize', 1);

        // Create the second file
        $logger->addDebug(__METHOD__);

        $logger->option('fileDetected', false);

        // Create the thrid file
        $logger->addDebug(__METHOD__);

        $logger->option('fileDetected', false);

        // Create the fouth file
        $logger->addDebug(__METHOD__);

        $logger->option('fileDetected', false);

        $newFile = $logger->getFile();

        $this->assertNotEquals($oldFile, $newFile);
    }
    
    public function testAllLevel()
    {
        $logger = $this->object;
        
        $file = $logger->option('file');
        
        foreach ($logger->option('levels') as $level => $p) {
            $uid = uniqid();
            $logger->{'add' . $level}($uid);
            $this->assertContains($uid, file_get_contents($file));
        }
    }

    public function testSetFileOption()
    {
        $logger = $this->object;
        
        $logger->option('level', 'debug');
        
        $newFile = dirname($logger->getFile()) . DIRECTORY_SEPARATOR . mt_rand(0, 1000);

        $logger->setFile($newFile);

        $logger->addDebug(__METHOD__);

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

        $file = $logger->getFile();

        $logger->setfileFormat('newfile.log');

        $logger->addDebug(__METHOD__);

        $file = dirname($file) . DIRECTORY_SEPARATOR . 'newfile.log';

        $this->assertFileExists($file);
    }

    public function testSetFileSizeOption()
    {
        $logger = $this->object;

        $logger->option('file', $logger->option('file'));

        $logger->addDebug(__METHOD__);

        $oldFile = $logger->option('file');

        // always create new file
        $logger->option('fileSize', 1);

        $logger->addDebug(__METHOD__);

        $newFile = $logger->option('file');

        $this->assertNotEquals($oldFile, $newFile);
    }
}
