<?php

namespace WeiTest;

class EnvTest extends TestCase
{
    public function setUp()
    {
        $this->wei->setConfig('env:configDir', __DIR__ . '/Fixtures/env/%env%.php');

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

    public function testsetName()
    {
        $env = $this->wei->newInstance('env', array(
            'envMap' => array(
                '127.0.0.1'  => 'dev',
                '127.0.0.2' => 'beta',
                '192.168.25.2' => 'prod'
            ),
            'server' => array(
                'SERVER_ADDR' => '127.0.0.1'
            )
        ));

        $env->detectEnvName();
        $this->assertEquals('dev', $env->getName());

        $env->setOption('server', array('SERVER_ADDR' => '127.0.0.2'));
        $env->detectEnvName();
        $this->assertEquals('beta', $env->getName());

        $env->setOption('server', array('SERVER_ADDR' => '192.168.25.2'));
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getName());

        $env->setOption('server', array('SERVER_ADDR' => '192.168.25.3'));
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getName());

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
        $env = new \Wei\Env(array(
            'wei' => $this->wei,
            'detector' => function(){
                return 'test';
            }
        ));

        $this->assertEquals('test', $env->getName());
    }

    public function testPreloadEnvWeiOverwriteConfigError()
    {
        $wei = new \Wei\Wei(array(
            'wei' => array(
                'debug' => true,
                'preload' => array(
                    'env'
                )
            ),
            'env' => array(
                'configDir' => __DIR__ . '/Fixtures/env/%env%.php',
                'server' => array(
                    'SERVER_ADDR' => '1.2.3.4'
                ),
                'envMap' => array(
                    '1.2.3.4' => 'test'
                )
            )
        ));

        $this->assertFalse($wei->isDebug());
        $this->assertFalse($wei->getConfig('wei:debug'));
    }
}
