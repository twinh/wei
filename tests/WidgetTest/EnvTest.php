<?php

namespace WidgetTest;

class EnvTest extends TestCase
{
    public function setUp()
    {
        $this->widget->config('env/configDir', __DIR__ . '/Fixtures/env/%env%.php');
        
        parent::setUp();
    }
    
    public function testEnvDetect()
    {
        $this->env->setEnv('dev');
        
        $this->assertTrue($this->env->inDev());
        $this->assertFalse($this->env->inTest());
        $this->assertFalse($this->env->inProd());
    }
    
    public function testLoadCliConfig()
    {
        // Load form __DIR__ . '/Fixtures/env/cli.php' when phpunit call setUp
        $this->assertTrue($this->widget->config('cli'));
    }
    
    public function testLoadConfigFormCurrentDeveloper()
    {
        $this->server['SERVER_ADMIN'] = 'twin@localhost';
        
        $env = new \Widget\Env(array(
            'widget' => $this->widget,
            'configDir' => __DIR__ . '/Fixtures/env/%env%.php'
        ));
        
        $this->assertTrue($this->widget->config('twin'));
    }
    
    public function testSetEnv()
    {
        $this->server['APPLICATION_ENV'] = 'custom';
        // reset env
        $this->env->setEnv(null);
        $this->assertEquals('custom', $this->env->getEnv());
        
        unset($this->server['APPLICATION_ENV']);
        $this->env->setEnv(null);
        $this->assertEquals('prod', $this->env->getEnv());
        
        $this->env->setEnv('mymachine');
        $this->assertEquals('mymachine', $this->env->getEnv());
    }
    
    public function testInvoker()
    {
        $this->env->setEnv('prod');
        
        $this->assertEquals('prod', $this->env());
    }
}