<?php

namespace WidgetTest;

class EnvTest extends TestCase
{
    public function setUp()
    {
        $this->widget->setConfig('env:configDir', __DIR__ . '/Fixtures/env/%env%.php');

        parent::setUp();
    }

    public function testEnvDetect()
    {
        $this->env->setEnv('dev');

        $this->assertTrue($this->env->isDev());
        $this->assertFalse($this->env->isTest());
        $this->assertFalse($this->env->isProd());
    }

    public function testLoadCliConfig()
    {
        // Load form __DIR__ . '/Fixtures/env/cli.php' when phpunit call setUp
        $this->assertTrue($this->widget->getConfig('cli'));
    }

    public function testSetEnv()
    {
        $env = $this->widget->newInstance('env', array(
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
        $this->assertEquals('dev', $env->getEnv());

        $env->setOption('server', array('SERVER_ADDR' => '127.0.0.2'));
        $env->detectEnvName();
        $this->assertEquals('beta', $env->getEnv());

        $env->setOption('server', array('SERVER_ADDR' => '192.168.25.2'));
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getEnv());

        $env->setOption('server', array('SERVER_ADDR' => '192.168.25.3'));
        $env->detectEnvName();
        $this->assertEquals('prod', $env->getEnv());

        $env->setEnv('myMachine');
        $this->assertEquals('myMachine', $env->getEnv());
    }

    public function testInvoker()
    {
        $this->env->setEnv('prod');

        $this->assertEquals('prod', $this->env());
    }

    public function testEnvDetectCallback()
    {
        $env = new \Widget\Env(array(
            'widget' => $this->widget,
            'detector' => function(){
                return 'test';
            }
        ));

        $this->assertEquals('test', $env->getEnv());
    }

    public function testPreloadEnvWidgetOverwriteConfigError()
    {
        $widget = new \Widget\Widget(array(
            'widget' => array(
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

        $this->assertFalse($widget->isDebug());
        $this->assertFalse($widget->getConfig('widget:debug'));
    }
}