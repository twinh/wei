<?php

namespace WeiTest;

/**
 * @property \Wei\Logger $logger
 *
 * @internal
 */
final class LoggerTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testLog()
    {
        $logger = $this->logger;

        $logger->debug(__METHOD__);

        $file = $logger->getFile();

        $this->assertStringContainsString(__METHOD__, file_get_contents($file));

        // clean all file in log directory
        $logger->clean();

        $logger->setHandledLevel('info');

        $logger->debug(__METHOD__);

        $this->assertFileNotExists($file);
    }

    public function testGetFile()
    {
        $logger1 = new \Wei\Logger([
            'wei' => $this->wei,
            'fileSize' => 1,
        ]);

        $logger1->debug(__METHOD__);

        $logger2 = new \Wei\Logger([
            'wei' => $this->wei,
            'fileSize' => 1,
        ]);

        $logger2->debug(__METHOD__);

        $logger3 = new \Wei\Logger([
            'wei' => $this->wei,
            'fileSize' => 1,
        ]);

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
            $logger->{$level}($uid);
            $this->assertStringContainsString($uid, file_get_contents($file));
        }
    }

    public function testFileSize()
    {
        $oldLogger = new \Wei\Logger([
            'wei' => $this->wei,
            'fileSize' => 1,
        ]);

        $oldFile = $oldLogger->getFile();

        $oldLogger->debug(__METHOD__);

        $newLogger = new \Wei\Logger([
            'wei' => $this->wei,
            'fileSize' => 1,
        ]);

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

        $this->assertStringContainsString('DEBUG', file_get_contents($file));

        $logger->clean();

        $logger->setLevel('info');

        // call by log method
        $logger->log('no this level', __METHOD__);

        $this->assertStringContainsString('INFO', file_get_contents($file));
    }

    public function testMkDirException()
    {
        $this->setExpectedException('RuntimeException', 'Fail to create directory "http://example/"');

        $logger = $this->wei->newInstance('logger', [
            'dir' => 'http://example/',
        ]);
        $logger->getFile();
    }

    public function testLogWithContext()
    {
        $this->logger->debug('debug', ['name' => 'value']);

        $content = file_get_contents($this->logger->getFile());

        $this->assertStringContainsString('debug', $content);
        $this->assertStringContainsString('name', $content);
        $this->assertStringContainsString('value', $content);
    }

    public function testLogWithDefaultContext()
    {
        $this->logger->setContext('name', 'value');

        $this->logger->setContext([
            'clientIp' => '127.0.0.1',
            'serverIp' => '127.0.0.1',
        ]);

        $this->logger->debug('log with default context');

        $content = file_get_contents($this->logger->getFile());

        $this->assertStringContainsString('log with default context', $content);
        $this->assertStringContainsString('name', $content);
        $this->assertStringContainsString('clientIp', $content);
        $this->assertStringContainsString('serverIp', $content);
    }

    public function testLogWithArrayMessage()
    {
        $this->logger->debug(['key' => 'value']);

        $content = file_get_contents($this->logger->getFile());

        $this->assertStringContainsString('key', $content);
        $this->assertStringContainsString('value', $content);
    }

    public function testLogWithExceptionMessage()
    {
        $e = new \Exception('test exception');
        $this->logger->error($e);

        $content = file_get_contents($this->logger->getFile());

        $this->assertStringContainsString('test exception', $content);
        $this->assertStringContainsString('"file": "' . __FILE__, $content);
    }

    public function testLoggerNamespace()
    {
        $this->logger->setOption([
            'namespace' => 'test',
            'format' => '%namespace% %message%',
        ]);
        $this->logger->info('log message');

        $content = file_get_contents($this->logger->getFile());
        $this->assertStringContainsString('test log message', $content);
    }

    public function providerForContexts()
    {
        return [
            ['debug'],
            ['info'],
            ['notice'],
            ['warning'],
            ['error'],
            ['critical'],
            ['alert'],
            ['emergency'],
        ];
    }

    /**
     * @dataProvider providerForContexts
     * @param mixed $method
     */
    public function testStringAsContext($method)
    {
        $this->logger->{$method}($method, $method);

        $content = file_get_contents($this->logger->getFile());

        $this->assertStringContainsString($method, $content);
    }
}
