<?php

namespace WidgetTest
{
    class FlushTest extends TestCase
    {
        public function tearDown()
        {
            parent::tearDown();

            /**
             * @link https://github.com/symfony/symfony/issues/2531
             * @link https://github.com/sebastianbergmann/phpunit/issues/390
             */
            if(ob_get_length() === 0) {
                ob_start();
            }
        }

        public function testInvoker()
        {
            $this->assertInstanceOf('\Widget\Flush', $this->flush());
            
            $this->assertEquals('0', ini_get('zlib.output_compression'));
            
            $this->assertEquals('1', ini_get('implicit_flush'));
            
            $this->assertEquals(0, ob_get_level());
        }
    }
}

namespace
{
    if (!function_exists('apache_setenv')) {
        function apache_setenv(){}
    }
}