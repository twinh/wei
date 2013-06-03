<?php

namespace WidgetTest;

class TwigTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('\Twig_Environment')) {
            $this->markTestSkipped('twig/twig is required');
            return;
        }

        $this->widget->config('twig', array(
            'paths' => __DIR__ . '/Fixtures'
        ));
        parent::setUp();
    }

    public function testInvoker()
    {
        $this->assertInstanceOf('\Twig_Environment', $this->twig());

        $content = $this->twig('twig.html.twig', array(
            'value' => __METHOD__
        ));

        $this->assertEquals(__METHOD__, $content);
    }

    public function testGetExtension()
    {
        $this->assertEquals('.html.twig', $this->twig->getExtension());
    }

    public function testRender()
    {
        $content = $this->twig->render('twig.html.twig', array(
            'value' => __METHOD__
        ));

        $this->assertEquals(__METHOD__, $content);
    }

    public function testDisplay()
    {
        $this->expectOutputString(__METHOD__);

        $this->twig->display('twig.html.twig', array(
            'value' => __METHOD__
        ));
    }

    public function testAssign()
    {
        $this->twig->assign('value', __METHOD__);

        $content = $this->twig->render('twig.html.twig');

        $this->assertEquals(__METHOD__, $content);
    }

    public function testAssignArray()
    {
        $this->twig->assign(array(
            'value' => __METHOD__
        ));

        $globals = $this->twig()->getGlobals();
        $this->assertEquals(__METHOD__, $globals['value']);
    }

    public function testGetDefaultVariable()
    {
        $globals = $this->twig()->getGlobals();
        $this->assertSame($this->widget, $globals['widget']);
    }
}