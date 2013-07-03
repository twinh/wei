<?php

namespace WidgetTest;

use Widget\Widget;

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

        $config = $widget->config();
        $this->assertEquals($widget->getOption('config'), $config);
    }

    public function testMergeConfig()
    {
        $this->widget->config(array(
            'widgetName' => array(
                'option1' => 'value1',
            )
        ));

        $this->widget->config(array(
            'widgetName' => array(
                'option2' => 'value2',
            )
        ));

        $this->assertEquals('value1', $this->widget->config('widgetName/option1'));
        $this->assertEquals('value2', $this->widget->config('widgetName/option2'));
    }

    public function testSetOptionInSetConfig()
    {
        $widget = $this->widget;
        $request = $widget->request;

        $widget->config('request', array(
            'method' => 'POST'
        ));
        $this->assertEquals('POST', $request->getMethod());

        // No effect at this time
        $widget->config('request/method', 'PUT');
        $this->assertEquals('POST', $request->getMethod());

        $widget->config(array(
            'request' => array(
                'method' => 'DELETE'
            )
        ));
        $this->assertEquals('DELETE', $request->getMethod());
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

    public function testSetInis()
    {
        $this->widget->setInis(array(
            'date.timezone' => 'Asia/Shanghai'
        ));
        $this->assertEquals('Asia/Shanghai', ini_get('date.timezone'));
    }

    public function testSet()
    {
        $request = new \Widget\Request(array(
            'widget' => $this->widget,
        ));

        $this->widget->set('request', $request);
        $this->assertSame($request, $this->widget->request);
    }

    public function testSetWithCaseSensitiveName()
    {
        $arrayCache = new \Widget\ArrayCache(array(
            'widget' => $this->widget
        ));

        $this->widget->set('myArrayCache', $arrayCache);

        $this->assertSame($arrayCache, $this->widget->get('myArrayCache'));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method "__invoke" not found in class "WidgetTest\Fixtures\WidgetWithoutInvokeMethod"
     */
    public function testInstanceWidgetWithoutInvokeMethodFromWidgetManager()
    {
        $this->widget->setOption('+aliases', array(
            'noInvokeMethod' => 'WidgetTest\Fixtures\WidgetWithoutInvokeMethod'
        ));

        $this->widget->noInvokeMethod;
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method "__invoke" not found in class "WidgetTest\Fixtures\WidgetWithoutInvokeMethod"
     */
    public function testInstanceWidgetWithoutInvokeMethod()
    {
        $this->widget->setOption('+aliases', array(
            'noInvokeMethod' => 'WidgetTest\Fixtures\WidgetWithoutInvokeMethod'
        ));

        $this->widget->request->noInvokeMethod;
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Property or widget "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file
     */
    public function testInstanceNotFoundWidgetFromWidgetManager()
    {
        $this->widget->notFoundWidget;
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Property or widget "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file
     */
    public function testInstanceNotFoundWidget()
    {
        $this->widget->request->notFoundWidget;
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method "Widget\Widget->notFoundWidget" or widget "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file
     */
    public function testInvokeNotFoundWidget()
    {
        $this->widget->notFoundWidget();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Property or method "notFoundWidget" not found
     */
    public function testInvokeNotFoundWidgetByCallUserFunc()
    {
        call_user_func($this->widget, 'notFoundWidget');
    }

    public function testGetFromDeps()
    {
        // Set options for sub request
        $this->widget->config('sub.request', array(
            'fromGlobal' => false,
            'data' => array(
                'id' => 'fromSubRequest'
            )
        ));

        $request = $this->widget->request;
        $request->set('id', 'fromOrigin');

        $widgetHasDeps = new \WidgetTest\Fixtures\WidgetHasDeps(array(
            'widget' => $this->widget
        ));

        // Instance request widget from 'request.sub' configuration
        $subRequest = $widgetHasDeps->request;
        $this->assertEquals('fromSubRequest', $subRequest->get('id'));

        $this->assertEquals('fromOrigin', $request->get('id'));
    }

    public function testDeps()
    {
        $widget = $this->widget;

        $widget->setOption('deps', array(
            'subRequest' => 'sub.request'
        ));

        $this->assertInstanceOf('Widget\Request', $widget->subRequest);
        $this->assertNotSame($widget->request, $widget->subRequest);

        $subRequest = $widget->request->subRequest;
        $this->assertInstanceOf('Widget\Request', $subRequest);
        unset($widget->request->subRequest);
    }

    public function testInvoke()
    {
        $this->request->set('id', __METHOD__);

        // Equals to $this->widget->request('id')
        $id = $this->widget->invoke('request', array('id'));

        $this->assertEquals(__METHOD__, $id);
    }

    public function testInvoker()
    {
        $request = $this->widget('request');

        $this->assertInstanceOf('\Widget\Request', $request);
    }

    public function testInstanceWidgetWithWidgetOption()
    {
        $widget = new \Widget\Widget(array(
            'widget' => array(
                'autoload' => false,
            ),
        ));

        $this->assertFalse($widget->getOption('autoload'));
    }

    public function testNewInstance()
    {
        $newRequest = $this->widget->newInstance('request');

        $this->assertInstanceOf('\Widget\Request', $newRequest);
        $this->assertEquals($this->request, $newRequest);
        $this->assertNotSame($this->request, $newRequest);
    }

    public function testGetClassFromAliases()
    {
        $this->widget->setOption('+aliases', array(
            'request' => '\Widget\Request'
        ));

        $this->assertEquals('\Widget\Request', $this->widget->getClass('request'));
    }

    public function testRemove()
    {
        // Instance request widget
        $request = $this->widget->request;

        $this->assertTrue($this->widget->remove('request'));
        $this->assertFalse($this->widget->remove('request'));

        // Re-instance request widget
        $this->assertNotSame($request, $this->widget->request);
    }

    public function testHas()
    {
        $this->widget->request;

        $this->assertEquals('\Widget\Request', $this->widget->has('request'));
        $this->assertFalse($this->widget->has('request2'));
    }

    public function testAutoload()
    {
        $this->widget->setAutoload(false);
        $this->assertNotContains(array($this->widget, 'autoload'), spl_autoload_functions());

        $this->widget->setAutoload(true);
        $this->assertContains(array($this->widget, 'autoload'), spl_autoload_functions());

        $this->widget->setAutoloadMap(array(
            'WidgetTest' => dirname(__DIR__)
        ));

        $this->assertTrue(class_exists('WidgetTest\Fixtures\Autoload'));
        $this->assertFalse(class_exists('WidgetTest\Fixtures\AutoloadNotFound'));
    }

    public function testImport()
    {
        $this->widget->setImport(array(
            array(
                'dir' => __DIR__ . '/Fixtures/Import',
                'namespace' => '\WidgetTest\Fixtures\Import',
                'format' => 'test%s',
            )
        ));

        $this->assertEquals('\WidgetTest\Fixtures\Import\Widget1', $this->widget->has('testWidget1'));
        $this->assertEquals('\WidgetTest\Fixtures\Import\Widget2', $this->widget->has('testWidget2'));
        $this->assertFalse($this->widget->has('testWidget3'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Fail to import widgets from non-exists directory
     */
    public function testImportInvalidDir()
    {
        $this->widget->import(__DIR__ . '/not found/', 'test');
    }

    public function testNewWidgetFromFactoryMethod()
    {
        $widget = Widget::create(array(), 'otherName');

        $this->assertInstanceOf('\Widget\Widget', $widget);
        $this->assertSame($widget, Widget::create(array(), 'otherName'));
    }

    public function testFileAsConfiguration()
    {
        $widget = Widget::create(__DIR__ . '/Fixtures/env/twin.php');

        $this->assertTrue($widget->config('twin'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Configuration should be array or file
     */
    public function testInvalidArgumentExceptionOnCreate()
    {
        Widget::create(new \stdClass);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Configuration should be array or file
     */
    public function testInvalidArgumentExceptionWhenFileNotFind()
    {
        Widget::create('not existing file');
    }

    public function testReset()
    {
        $first = Widget::create(array(), 'first');
        $second = Widget::create(array(), 'second');

        $this->assertTrue(Widget::reset('first'));

        $this->assertNotSame($first, Widget::create(array(), 'second'));
        $this->assertSame($second, Widget::create(array(), 'second'));

        $this->assertTrue(Widget::reset());
        $this->assertNotSame($first, Widget::create(array(), 'second'));
        $this->assertNotSame($second, Widget::create(array(), 'second'));

        $this->assertFalse(Widget::reset('NotInstance'));
    }

    public function testWidgetFunction()
    {
        $this->assertSame(Widget::create(), widget());
    }

    public function testGetSelf()
    {
        $this->assertSame($this->widget, $this->widget->widget);
        $this->assertSame($this->widget, $this->widget->widget->widget);
    }

    public function testDebug()
    {
        $this->widget->setDebug(false);
        $this->assertFalse($this->widget->inDebug());

        $this->widget->setDebug(true);
        $this->assertTrue($this->widget->inDebug());
    }
}
