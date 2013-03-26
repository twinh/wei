<?php

namespace WidgetTest;

class ViewTest extends TestCase
{
    /**
     * @var Widget\View
     */
    protected $object;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->object->setOption('dirs', array(
            __DIR__ . '/Fixtures'
        ));
    }
    
    public function testInvoker()
    {
        $view = $this->object;
        $this->assertInstanceOf('Widget\View', $view());
    }
    
    public function testAssign()
    {
        $view = $this->object;
        $value = __METHOD__;
        
        $view->assign('content', $value);
        $content = $view->render('layout.php');
        
        $this->assertContains($value, $content);
    }
    
    public function testAssinArray()
    {
        $view = $this->object;
        
        $view->assign(array(
            'key' => 'value',
            'key2' => 'value2',
        ));
        
        $this->assertEquals('value', $view->get('key'));
        $this->assertEquals('value2', $view->get('key2'));
        $this->assertNull($view->get('not-defined-key'));
    }
    
    /**
     * @expectedException Widget\Exception\NotFoundException
     */
    public function testFileNotFoundException()
    {
        $this->object->render('not-found-file');
    }
    
    public function testLayout()
    {
        $content = $this->object->render('view.php');
        
        $this->assertContains('layout start', $content);
        $this->assertContains('layout end', $content);
    }
    
    public function testDisplay()
    {
        ob_start();
        $this->object->display('layout.php', array('content' => 'test'));
        $content = ob_get_clean();

        $expected = $this->object->render('layout.php', array('content' => 'test'));
        
        $this->assertEquals($expected, $content);
    }
}