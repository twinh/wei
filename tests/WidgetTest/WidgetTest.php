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
        $widget->setConfig(array(
            'test' => array(
                'a' => array(
                    'b' => array(
                        'c' => false
                    )
                )
            )
        ));

        $this->assertSame(array('a' => array('b' => array('c' => false))), $widget->getConfig('test'));
        $this->assertSame(array('b' => array('c' => false)), $widget->getConfig('test:a'));
        $this->assertSame(array('c' => false), $widget->getConfig('test:a:b'));
        $this->assertSame(false, $widget->getConfig('test:a:b:c'));
        $this->assertSame(null, $widget->getConfig('test:noThisKey'));
        $this->assertSame(false, $widget->getConfig('test:noThisKey', false));
        $this->assertSame(0, $widget->getConfig('test:noThisKey', 0));

        $widget->setConfig('test2', array());
        $this->assertSame(array(), $widget->getConfig('test2'));

        $widget->setConfig('test2:a', 'b');
        $this->assertSame('b', $widget->getConfig('test2:a'));
        $this->assertSame(array('a' => 'b'), $widget->getConfig('test2'));

        $widget->setConfig('test2:a:b', 'c');
        $this->assertSame('c', $widget->getConfig('test2:a:b'));

        $widget->setConfig('test.request', array());
        $deps = $widget->getOption('deps');
        $this->assertArrayHasKey('testRequest', $deps);
        $this->assertSame('test.request', $deps['testRequest']);
    }

    public function testMergeConfig()
    {
        $widget = $this->widget;

        $widget->setConfig(array(
            'widgetName' => array(
                'option1' => 'value1',
            )
        ));

        $widget->setConfig(array(
            'widgetName' => array(
                'option2' => 'value2',
            )
        ));

        $this->assertEquals('value1', $widget->getConfig('widgetName:option1'));
        $this->assertEquals('value2', $widget->getConfig('widgetName:option2'));

        $widget->setConfig(array(
            'widgetName' => array(
                'option1' => 'value3',
            )
        ));

        $this->assertEquals('value3', $widget->getConfig('widgetName:option1'));
        $this->assertEquals('value2', $widget->getConfig('widgetName:option2'));
    }

    public function testSetOptionInSetConfig()
    {
        $widget = $this->widget;
        $request = $widget->request;

        $widget->setConfig('request', array(
            'method' => 'POST'
        ));
        $this->assertEquals('POST', $request->getMethod());

        // No effect at this time
        $widget->setConfig('request/method', 'PUT');
        $this->assertEquals('POST', $request->getMethod());

        $widget->setConfig(array(
            'request' => array(
                'method' => 'DELETE'
            )
        ));
        $this->assertEquals('DELETE', $request->getMethod());
    }

    public function testGetConfigs()
    {
        $configs = $this->widget->getConfig();
        $this->assertInternalType('array', $configs);
    }

    public function testInvalidArgumentExceptionForWidgetOption()
    {
        $this->setExpectedException('InvalidArgumentException');
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
        $widget = $this->widget;

        $widget->setInis(array(
            'date.timezone' => 'Asia/Shanghai'
        ));

        $this->assertEquals('Asia/Shanghai', ini_get('date.timezone'));
        $this->assertIsSubset(array('date.timezone' => 'Asia/Shanghai'), $widget->getOption('inis'));

        $widget->setInis(array(
            'date.default_latitude' => '31.7667'
        ));
        $this->assertIsSubset(array(
            'date.timezone' => 'Asia/Shanghai',
            'date.default_latitude' => '31.7667'
        ), $widget->getOption('inis'));
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

    public function testInstanceNotFoundWidgetFromWidgetManager()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or object "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file'
        );
        $this->widget->notFoundWidget;
    }

    public function testInstanceNotFoundWidget()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or object "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file'
        );
        $this->widget->request->notFoundWidget;
    }

    public function testInvokeNotFoundWidget()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Method "Widget\Widget->notFoundWidget" or object "notFoundWidget" (class "Widget\NotFoundWidget") not found, called in file'
        );
        $this->widget->notFoundWidget();
    }

    public function testInvokeNotFoundWidgetByCallUserFunc()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or method "notFoundWidget" not found'
        );
        $this->widget->get('notFoundWidget');
    }

    public function testGetFromDeps()
    {
        // Set options for sub request
        $this->widget->setConfig('sub.request', array(
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

    public function testPreloadWidgetInDependenceMap()
    {
        $widget = new Widget(array(
            'widget' => array(
                'deps' => array(
                    'request' => 'sub.request',
                ),
                'preload' => array(
                    'request'
                )
            ),
            'request' => array(
                'fromGlobal' => true,
            ),
            'sub.request' => array(
                'fromGlobal' => false,
            )
        ));

        $this->assertFalse($widget->request->getOption('fromGlobal'));
    }

    public function testInvoke()
    {
        $this->request->set('id', __METHOD__);

        // Equals to $this->widget->request('id')
        $id = $this->widget->invoke('request', array('id'));

        $this->assertEquals(__METHOD__, $id);
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
        $widget = new Widget(array(
            'widget' => array(
                'autoload' => false,
                'import' => array(
                    array(
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WidgetTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true
                    )
                )
            )
        ));

        $this->assertEquals('\WidgetTest\Fixtures\Import\Widget1', $widget->has('testWidget1'));
        $this->assertEquals('\WidgetTest\Fixtures\Import\Widget2', $widget->has('testWidget2'));
        $this->assertFalse($this->widget->has('testWidget3'));
    }

    public function testImportInvalidDir()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Fail to import classes from non-exists directory'
        );
        $this->widget->import(__DIR__ . '/not found/', 'test');
    }

    public function testImportWithAutoload()
    {
        $widget = new Widget(array(
            'widget' => array(
                'autoload' => false,
                'import' => array(
                    array(
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WidgetTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true
                    )
                )
            )
        ));

        $map = $widget->getOption('autoloadMap');
        $this->assertArrayHasKey('\WidgetTest\Fixtures\Import', $map);
    }

    public function testFileAsConfiguration()
    {
        $widget = Widget::getContainer(__DIR__ . '/Fixtures/env/twin.php');

        $this->assertTrue($widget->getConfig('twin'));
    }

    public function testInvalidArgumentExceptionOnCreate()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Configuration should be array or file'
        );
        Widget::getContainer(new \stdClass);
    }

    public function testInvalidArgumentExceptionWhenFileNotFind()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Configuration should be array or file'
        );
        Widget::getContainer('not existing file');
    }

    public function testSetContainer()
    {
        $widget = Widget::getContainer();
        $newWidget = new Widget();

        Widget::setContainer($newWidget);
        $this->assertSame($newWidget, Widget::getContainer());

        // reset
        Widget::setContainer($widget);
    }

    public function testWidgetFunction()
    {
        $this->assertSame(Widget::getContainer(), widget());
    }

    public function testWeiFunction()
    {
        $this->assertSame(Widget::getContainer(), wei());
    }

    public function testGetSelf()
    {
        $this->assertSame($this->widget, $this->widget->widget);
        $this->assertSame($this->widget, $this->widget->widget->widget);
    }

    public function testDebug()
    {
        $this->widget->setDebug(false);
        $this->assertFalse($this->widget->isDebug());

        $this->widget->setDebug(true);
        $this->assertTrue($this->widget->isDebug());
    }

    public function testSetAlias()
    {
        $this->widget->setAlias('widgetName', 'className');
        $aliases = $this->widget->getOption('aliases');

        $this->assertArrayHasKey('widgetName', $aliases);
        $this->assertEquals('className', $aliases['widgetName']);
    }

    public function testIsInstanced()
    {
        $this->assertTrue($this->widget->isInstanced('widget'));
        $this->widget->remove('widgetName');
        $this->assertFalse($this->widget->isInstanced('widgetName'));
    }

    public function testConstructCallback()
    {
        $beforeConstruct = $afterConstruct = array();
        $widget = new Widget(array(
            'widget' => array(
                'autoload' => false,
                'beforeConstruct' => function($widget, $fullName, $name) use(&$beforeConstruct) {
                    $beforeConstruct[] = $fullName;
                },
                'afterConstruct' => function($widget, $fullName, $name, $object) use(&$afterConstruct) {
                    $afterConstruct[] = $fullName;
                }
            )
        ));

        // Instance widgets
        $widget->request;
        $widget->{'sub.request'};

        $this->assertContains('request', $beforeConstruct);
        $this->assertContains('sub.request', $afterConstruct);
        $this->assertContains('request', $beforeConstruct);
        $this->assertContains('sub.request', $afterConstruct);
    }
}
