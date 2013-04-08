<?php

namespace WidgetTest;

class MonologTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('\Monolog\Logger')) {
            $this->markTestSkipped('The monolog/monolog is not loaded');
        }
        
        parent::setUp();
    }
    
    public function testInvoker()
    {
        $this->assertInstanceOf('\Monolog\Logger', $this->monolog());
    }
    
    public function testCustomHandler()
    {
        $monologWidget = new \Widget\Monolog(array(
            'handlers' => array(
                new \Monolog\Handler\StreamHandler('php://stderr')
            )
        ));
        
        $monolog = $monologWidget();
        
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $monolog->popHandler());
    }
    
    /**
     * @expectedException \Widget\Exception\InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        new \Widget\Monolog(array(
            'handlers' => array(
                new \stdClass
            )
        ));
    }
    
    public function testLog()
    {
        $handler = new \Monolog\Handler\TestHandler;
        $monologWidget = new \Widget\Monolog(array(
            'handlers' => array(
                $handler
            )
        ));
        
        $monologWidget(\Monolog\Logger::ALERT, 'alert message');
        
        $this->assertTrue($handler->hasAlert('alert message'));
    }
}