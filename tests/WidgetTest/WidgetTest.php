<?php

namespace WidgetTest;

class WidgetTest extends TestCase
{
    public function createUserWidget()
    {
        return new \WidgetTest\Fixtures\User(array(
            'widget' => $this->widget,
            'name' => 'twin'
        ));
    }


    public function testConfig()
    {
        $widget = $this->widget;
        
        $widget->config('key', 'value');

        $this->assertEquals('value', $widget->config('key'));

        $widget->config('first/second', 'value2');

        $this->assertEquals(array('second' => 'value2',), $widget->config('first'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionForWidgetOption()
    {
        new \Widget\Request(array(
            'widget' => new \stdClass
        ));
    }
    
    public function testAppendOption()
    {
        $request = $this->request;
        
        $request->setOption('posts', array('a' => 'b'));
        $this->assertEquals(array('a' => 'b'), $request->getOption('posts'));
        
        $request->setOption('+posts', array('c' => 'd'));
        $this->assertEquals(array('a' => 'b', 'c' => 'd'), $request->getOption('posts'));
        
        $this->request->setOption('post', array());
    }
    
    public function testGetOption()
    {
        $user = $this->createUserWidget();
        
        $this->assertEquals('twin', $user->getName());
        
        $this->assertEquals('twin', $user->getOption('name'));
        
        $options = $user->getOption();
        
        $this->assertArrayHasKey('name', $options);
        $this->assertArrayHasKey('widget', $options);
        $this->assertArrayHasKey('deps', $options);
    }
}
