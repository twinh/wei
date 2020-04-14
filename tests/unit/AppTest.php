<?php

namespace WeiTest;

/**
 * @property \Wei\App $app The application service
 * @method \Wei\App app()
 * @property \Wei\Request $request
 */
class AppTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->view->setDirs(__DIR__ . '/Fixtures/App/views');

        $this->app->setOption('controllerFormat', 'WeiTest\Fixtures\App\Controller\%controller%');
    }

    protected function tearDown(): void
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testBaseApp()
    {
        // WeiTest\App\Controller\Test::testAction
        $this->app->dispatch('example', 'test');

        $this->expectOutputString('test');
    }

    public function testControllerNotFound()
    {
        // Returns array result
        $result = $this->app->dispatch('ControllerNotFound', 'index', array(), false);

        $this->assertInternalType('array', $result);

        $this->assertEquals(array(
            'controllers' => array(
                'ControllerNotFound' => array(
                    'WeiTest\Fixtures\App\Controller\ControllerNotFound'
                )
            )
        ), $result);

        // Throw 404 exception when application not found
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", array(
                'The page you requested was not found',
                ' - controller "ControllerNotFound" not found'
            )),
            404
        );
        $this->app->dispatch('ControllerNotFound');
    }

    public function testNestedController()
    {
        $result = $this->app->dispatch('admin/index');

        $this->assertInstanceOf('\Wei\Response', $result);

        $this->expectOutputString('admin.index');
    }

    public function testNestedControllerNotFound()
    {
        $result = $this->app->dispatch('Controller\Admin', 'index', array(), false);

        $this->assertInternalType('array', $result);

        $this->assertEquals(array(
            'controllers' => array(
                'Controller\Admin' => array(
                    'WeiTest\Fixtures\App\Controller\Controller\Admin'
                )
        )), $result);
    }

    public function testActionNotFound()
    {
        $result = $this->app->dispatch('example', 'ActionNotFound', array(), false);

        $this->assertEquals(array(
            'actions' => array(
                'ActionNotFound' => array(
                    'example' => array(
                        'WeiTest\Fixtures\App\Controller\Example'
                    )
                )
            )
        ), $result);
    }

    public function testActionReturnArrayAsViewParameter()
    {
        $this->expectOutputString('value');

        // WeiTest\App\Controller\Example::returnArrayAction
        $this->app->dispatch('example', 'returnArray');
    }

    public function testActionReturnResponseService()
    {
        $this->expectOutputString('response content');

        // WeiTest\App\Controller\Example::returnResponseAction
        $result = $this->app->dispatch('example', 'returnResponse');

        $this->assertInstanceOf('\Wei\Response', $result);
    }

    public function testActionReturnUnexpectedType()
    {
        $this->setExpectedException('InvalidArgumentException');

        // WeiTest\App\Controller\Example::returnUnexpectedTypeAction
        $this->app->dispatch('example', 'returnUnexpectedType');
    }

    public function testDispatchBreak()
    {
        $this->expectOutputString('stop');

        $this->app->dispatch('example', 'dispatchBreak');
    }

    public function testDispatchBreakInConstructor()
    {
        $this->expectOutputString('');

        $this->app->dispatch('controller', 'dispatchBreak', array(), false);
    }

    public function testForwardAction()
    {
        $this->expectOutputString('target');

        $this->request->setPathInfo('example/forwardAction');
        $this->app();
    }

    public function testForwardActionByDispatch()
    {
        $this->expectOutputString('target');

        $this->app->dispatch('example', 'forwardAction');
    }

    public function testForwardController()
    {
        $this->expectOutputString('index');

        $this->app->dispatch('example', 'forwardController');
    }

    public function testForwardParams()
    {
        $this->expectOutputString('forwardParamsAction');

        $this->app->dispatch('example', 'forwardParams');
    }

    public function testForwardDispatchReturnsNewResponse()
    {
        $this->expectOutputString('forwardParamsAction');

        $response = $this->app->dispatch('example', 'forwardParams');

        $this->assertInstanceOf('\Wei\Response', $response);

        $this->assertEquals('forwardParamsAction', $response->getContent());
    }

    public function testNestedControllerView()
    {
        $this->expectOutputString('value');

        $result = $this->app->dispatch('admin/index', 'view');

        $this->assertInstanceOf('\Wei\Response', $result);
    }

    /**
     * @dataProvider providerForActionReturnValue
     */
    public function testActionReturnValue($action, $output)
    {
        $this->expectOutputString($output);

        $this->app->dispatch('example', $action);
    }

    public function providerForActionReturnValue()
    {
        return array(
            array(
                'returnInt',
                '123'
            ),
            array(
                'returnNull',
                '',
            ),
            array(
                'returnFloat',
                '1.1',
            ),
            array(
                'returnTrue',
                '1'
            ),
            array(
                'returnFalse',
                ''
            )
        );
    }

    public function testActionParameters()
    {
        $this->expectOutputString('1');

        $this->app->dispatch('example', 'parameter', array('id' => '1'));
    }

    public function testActionIsProtected()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", array(
                'The page you requested was not found',
                ' - method "showAction" not found in controller "example"'
            )),
            '404'
        );

        $this->request->setPathInfo('example/protect');
        $this->app();
    }

    public function testActionNameCaseSensitive()
    {
        $this->expectOutputString('caseInsensitiveAction');

        $this->request->setPathInfo('example/caseInsensitive');
        $this->app();
    }

    public function testActionNameCaseSensitiveException()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", array(
                'The page you requested was not found',
                ' - method "showAction" not found in controller "example"'
            )),
            '404'
        );

        $this->request->setPathInfo('example/caseinsensitive');
        $this->app();
    }

    public function testNamespace()
    {
        $this->assertEquals('', $this->app->getNamespace());

        $this->request->set(array(
            'namespace' => 'test',
        ));
        $this->assertEquals('test', $this->app->getNamespace());
    }

    public function testGetSetController()
    {
        $this->assertEquals('index', $this->app->getController());

        $this->app->setController('newController');

        $this->assertEquals('newController', $this->app->getController());
    }

    public function testGetSetAction()
    {
        $this->assertEquals('index', $this->app->getAction());

        $this->app->setAction('newAction');

        $this->assertEquals('newAction', $this->app->getAction());
    }

    public function testDebugDetailMessageForController()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", array(
                'The page you requested was not found',
                ' - controller "notFound" not found (class "WeiTest\Fixtures\App\Controller\NotFound")'
            )),
            404
        );

        $this->request->set('debug-detail', '1');
        $this->app->dispatch('notFound');
    }

    public function testDebugDetailMessageForAction()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", array(
                'The page you requested was not found',
                ' - method "notFoundAction" not found in controller "example" (class "WeiTest\Fixtures\App\Controller\Example")'
            )),
            404
        );

        $this->request->set('debug-detail', '1');
        $this->app->dispatch('example', 'notFound');
    }

    public function testMiddlewareOnlyAction()
    {
        $this->expectOutputString('only');

        $this->app->dispatch('middleware', 'only');
    }

    public function testMiddlewareExceptAction()
    {
        $this->expectOutputString('except');

        $this->app->dispatch('middleware', 'except');
    }

    public function testBeforeMiddleware()
    {
        $this->expectOutputString('Before Middleware');

        $this->app->dispatch('middleware', 'before');
    }

    public function testAfterMiddleware()
    {
        $this->expectOutputString('After Middleware');

        $response = $this->app->dispatch('middleware', 'after');

        $this->assertEquals('After Middleware', $response->getContent());
    }

    public function testAroundMiddleware()
    {
        $this->expectOutputString('Not Found');

        $response = $this->app->dispatch('middleware', 'around');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getContent());
    }

    public function testStackMiddleware()
    {
        $this->expectOutputString('start1-start2-start3-stack-end3-end2-end1');

        $response = $this->app->dispatch('middleware', 'stack');

        $this->assertEquals('start1-start2-start3-stack-end3-end2-end1', $response->getContent());
    }

    public function testBeforeAndAfter()
    {
        $this->expectOutputString('callback');

        $this->request->set(array('before' => 0, 'after' => 0));
        $this->app->dispatch('callback');

        $this->assertEquals(1, $this->request['before']);
        $this->assertEquals(1, $this->request['after']);
    }

    public function testReturnStringMiddleware()
    {
        $this->expectOutputString('string');

        $this->app->dispatch('middleware', 'returnString');
    }

    public function testReturnArrayMiddleware()
    {
        $this->expectOutputString('arrayForView');

        $this->app->dispatch('middleware', 'returnArray');
    }
}
