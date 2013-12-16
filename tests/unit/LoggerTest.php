<?php

namespace WeiTest;

/**
 * @property \Wei\Logger $logger
 */
class LoggerTest extends TestCase
{
    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testLog()
    {
        $logger = $this->logger;

        $logger->debug(__METHOD__);

        $file = $logger->getFile();

        $this->assertContains(__METHOD__, file_get_contents($file));

        // clean all file in log directory
        $logger->clean();

        $logger->setHandledLevel('info');

        $logger->debug(__METHOD__);

        $this->assertFileNotExists($file);
    }

    public function testGetFile()
    {
        $logger1 = new \Wei\Logger(array(
            'wei' => $this->wei,
            'fileSize' => 1,
        ));

        $logger1->debug(__METHOD__);

        $logger2 = new \Wei\Logger(array(
            'wei' => $this->wei,
            'fileSize' => 1,
        ));

        $logger2->debug(__METHOD__);

        $logger3 = new \Wei\Logger(array(
            'wei' => $this->wei,
            'fileSize' => 1,
        ));

        $logger3->debug(__METHOD__);

        $this->assertNotEquals($logger1->getFile(), $logger2->getFile());
    }

    public function testAllLevel()
    {
        $logger = $this->logger;

        $logger->setHandledLevel('debug');

        $file = $logger->getFile();

        foreach ($logger->getOption('levels') as $level => $p) {
            $uid = uniqid();
            $logger->$level($uid);
            $this->assertContains($uid, file_get_contents($file));
        }
    }

    public function testFileSize()
    {
        $oldLogger = new \Wei\Logger(array(
            'wei' => $this->wei,
            'fileSize' => 1,
        ));

        $oldFile = $oldLogger->getFile();

        $oldLogger->debug(__METHOD__);

        $newLogger = new \Wei\Logger(array(
            'wei' => $this->wei,
            'fileSize' => 1,
        ));

        $newFile = $newLogger->getFile();

        $this->assertNotEquals($oldFile, $newFile);
    }

    public function testSetLevel()
    {
        $logger = $this->logger;

        $file = $logger->getFile();

        $logger->setLevel('debug');

        // call by __invoke method
        $logger('no this level', __METHOD__);

        $this->assertContains('DEBUG', file_get_contents($file));

        $logger->clean();

        $logger->setLevel('info');

        // call by log method
        $logger->log('no this level', __METHOD__);

        $this->assertContains('INFO', file_get_contents($file));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMkdirException()
    {
        $logger = $this->wei->newInstance('logger', array(
            'dir' => 'http://example/'
        ));
        $logger->getFile();
    }

    public function testLogWithContext()
    {
        $this->logger->debug('debug', array('key' => 'value'));

        $content = file_get_contents($this->logger->getFile());

        $this->assertContains('debug', $content);
        $this->assertContains('key', $content);
        $this->assertContains('value', $content);
    }
}
