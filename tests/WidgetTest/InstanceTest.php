<?php

namespace WidgetTest;

class InstanceTest extends TestCase
{
    /**
     * @dataProvider argsProvider
     */
    public function testInvoker($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        /* @var $instance \WidgetTest\Fixtures\Instance */
        $instance = $this->instance('\WidgetTest\Fixtures\Instance', func_get_args());
        
        $this->assertEquals($arg1, $instance->arg1);
        $this->assertEquals($arg2, $instance->arg2);
        $this->assertEquals($arg3, $instance->arg3);
        $this->assertEquals($arg4, $instance->arg4);
    }
    
    public function testClassNotFound()
    {
        $this->assertFalse($this->instance('ClassNotFound'));
    }
    
    public function testClassWithoutConstructor()
    {
        $this->instance('\stdClass', array(1, 2, 3,4 ));
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