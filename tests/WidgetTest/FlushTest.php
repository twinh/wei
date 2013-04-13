<?php

namespace WidgetTest;

class FlushTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
        
        /**
         * @link https://github.com/symfony/symfony/issues/2531
         * @link https://github.com/sebastianbergmann/phpunit/issues/390
         */
        if(ob_get_length() == 0) {
            ob_start();
        }
    }
    
    public function testInvoker()
    {
        $this->assertInstanceOf('\Widget\Flush', $this->flush());
    }
}