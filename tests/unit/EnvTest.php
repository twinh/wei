<?php

namespace WeiTest;

/**
 * @internal
 */
final class EnvTest extends TestCase
{
    protected function setUp(): void
    {
        $this->wei->setConfig('env:configFile', __DIR__ . '/Fixtures/env/%env%.php');

        parent::setUp();
    }

    public function testEnvDetect()
    {
        $this->env->setName('dev');

        $this->assertTrue($this->env->isDev());
        $this->assertFalse($this->env->isTest());
        $this->assertFalse($this->env->isProd());
    }

    public function testLoadCliConfig()
    {
        // Load form __DIR__ . '/Fixtures/env/cli.php' when phpunit call setUp
        $this->assertTrue($this->wei->getConfig('cli'));
    }

    public function testSetName()
    {
        $env = $this->wei->newInstance('env', [
            'ipMap' => [
                '127.0.0.1' => 'dev',
                '127.0.0.2' => 'beta',
                '192.168.25.2' => 'prod',
                '127.0.0.3' => 'prod-project-name',
            ],
            'server' => [
                'SERVER_ADDR' => '127.0.0.1',
            ],
        ]);

        $env->detectEnvName();
        $this->assertEquals('dev', $env->getName());

        $env->setOption('server', ['SERVER_ADDR' => '127.0.0.2']);
        $env->detectEnvName();
        $this->assertEquals('beta', $env->getName());

        $env->setOption('server', ['SERVER_ADDR' => '192.168.25.2']);
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getName());

        $env->setOption('server', ['SERVER_ADDR' => '192.168.25.3']);
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getName());

        $env->setOption('server', ['SERVER_ADDR' => '127.0.0.3']);
        $env->detectEnvName();
        $this->assertEquals('prod-project-name', $env->getName());
        $this->assertTrue($env->isProd());

        $env->setName('myMachine');
        $this->assertEquals('myMachine', $env->getName());
    }

    public function testInvoker()
    {
        $this->env->setName('prod');

        $this->assertEquals('prod', $this->env());
    }

    public function testEnvDetectCallback()
    {
        $env = new \Wei\Env([
            'wei' => $this->wei,
            'detector' => function () {
                return 'test';
            },
        ]);

        $this->assertEquals('test', $env->getName());
    }

    public function testEnvDetectorReturnsFalse()
    {
        $env = new \Wei\Env([
            'wei' => $this->wei,
            'detector' => function () {
                return false;
            },
            'ipMap' => [
                '127.0.0.1' => 'testing',
            ],
            'server' => [
                'SERVER_ADDR' => '127.0.0.1',
            ],
        ]);

        $this->assertEquals('testing', $env->getName());
    }

    public function testPreloadEnvWeiOverwriteConfigError()
    {
        $wei = new \Wei\Wei([
            'wei' => [
                'debug' => true,
                'preload' => [
                    'env',
                ],
            ],
            'env' => [
                'configFile' => __DIR__ . '/Fixtures/env/%env%.php',
                'server' => [
                    'SERVER_ADDR' => '1.2.3.4',
                ],
                'ipMap' => [
                    '1.2.3.4' => 'test',
                ],
            ],
        ]);

        $this->assertFalse($wei->isDebug());
        $this->assertFalse($wei->getConfig('wei:debug'));
    }

    public function testLodConfigFileReturnsEnvService()
    {
        $this->assertInstanceOf('\Wei\Env', $this->object->loadConfigFile('some file'));
    }
}
