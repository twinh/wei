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

        $logger->addDebug(__METHOD__);

        $file = $logger->getFile();

        $this->assertContains(__METHOD__, file_get_contents($file));

        // clean all file in log diretory
        $logger->clean();

        $logger->setHandledLevel('info');

        $logger->addDebug(__METHOD__);
        
        $this->assertFileNotExists($file);
    }

    public function testGetFile()
    {
        $logger1 = new \Widget\Log(array(
            'widget' => $this->widget,
            'fileSize' => 1,
        ));
        
        $logger1->addDebug(__METHOD__);
        
        $logger2 = new \Widget\Log(array(
            'widget' => $this->widget,
            'fileSize' => 1,
        ));
        
        $logger2->addDebug(__METHOD__);
        
        $logger3 = new \Widget\Log(array(
            'widget' => $this->widget,
            'fileSize' => 1,
        ));
        
        $logger3->addDebug(__METHOD__);
        
        $this->assertNotEquals($logger1->getFile(), $logger2->getFile());
    }
    
    public function testAllLevel()
    {
        $logger = $this->object;
        
        $file = $logger->getFile();
        
        foreach ($logger->option('levels') as $level => $p) {
            $uid = uniqid();
            $logger->{'add' . $level}($uid);
            $this->assertContains($uid, file_get_contents($file));
        }
    }

    public function testFileSize()
    {
        $oldLogger = new \Widget\Log(array(
            'widget' => $this->widget,
            'fileSize' => 1,
        ));
        
        $oldFile = $oldLogger->getFile();
        
        $oldLogger->addDebug(__METHOD__);

        $newLogger = new \Widget\Log(array(
            'widget' => $this->widget,
            'fileSize' => 1,
        ));
        
        $newFile = $newLogger->getFile();

        $this->assertNotEquals($oldFile, $newFile);
    }
    
    public function testSetLevel()
    {
        $logger = $this->object;
        
        $file = $logger->getFile();
        
        $logger->setLevel('debug');
        
        $logger(__METHOD__);
        
        $this->assertContains('DEBUG', file_get_contents($file));
        
        $logger->clean();
        
        $logger->setLevel('info');
        
        $logger(__METHOD__);
        
        $this->assertContains('INFO', file_get_contents($file));
    }
}
