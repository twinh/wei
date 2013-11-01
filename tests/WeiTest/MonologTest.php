<?php

namespace WeiTest;

class MonologTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('\Monolog\Logger')) {
            $this->markTestSkipped('The monolog/monolog is not loaded');
            return;
        }

        parent::setUp();
    }

    public function testInvoker()
    {
        $this->assertInstanceOf('\Monolog\Logger', $this->monolog());
    }

    public function testCustomHandler()
    {
        $monologWei = new \Wei\Monolog(array(
            'handlers' => array(
                new \Monolog\Handler\StreamHandler('php://stderr')
            )
        ));

        $monolog = $monologWei();

        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $monolog->popHandler());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        new \Wei\Monolog(array(
            'handlers' => array(
                new \stdClass
            )
        ));
    }

    public function testLog()
    {
        $handler = new \Monolog\Handler\TestHandler;
        $monologWei = new \Wei\Monolog(array(
            'handlers' => array(
                $handler
            )
        ));

        $monologWei(\Monolog\Logger::ALERT, 'alert message');

        $this->assertTrue($handler->hasAlert('alert message'));
    }

    /**
     * @dataProvider argsProvider
     */
    public function testCreateInstance($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        /* @var $instance \WeiTest\Fixtures\Instance */
        $instance = $this->monolog->createInstance('\WeiTest\Fixtures\Instance', func_get_args());

        $this->assertEquals($arg1, $instance->arg1);
        $this->assertEquals($arg2, $instance->arg2);
        $this->assertEquals($arg3, $instance->arg3);
        $this->assertEquals($arg4, $instance->arg4);
    }

    public function testClassNotFound()
    {
        $this->assertFalse($this->monolog->createInstance('ClassNotFound'));
    }

    public function testClassWithoutConstructor()
    {
        $this->monolog->createInstance('\stdClass', array(1, 2, 3,4 ));
    }

    public function argsProvider()
    {
        return array(
            array(

            ),
            array(
                1
            ),
            array(
                1, 2
            ),
            array(
                1, 2, 3
            ),
            array(
                1, 2, 3, 4
            )
        );
    }
}
