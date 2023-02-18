<?php

namespace WeiTest;

use WeiTest\Fixtures\App\Controller\Middleware;
use WeiTest\Fixtures\App\Middleware\All;

/**
 * @property \Wei\App $app The application service
 * @method \Wei\App app()
 * @property \Wei\Req $req
 *
 * @internal
 */
final class AppTest extends TestCase
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
        // WeiTest\App\Controller\Test::exampleAction
        $this->app->dispatch('example', 'example');

        $this->expectOutputString('test');
    }

    public function testControllerNotFound()
    {
        // Returns array result
        $result = $this->app->dispatch('ControllerNotFound', 'index', [], false);

        $this->assertIsArray($result);

        $this->assertEquals([
            'controllers' => [
                'ControllerNotFound' => [
                    'WeiTest\Fixtures\App\Controller\ControllerNotFound',
                ],
            ],
        ], $result);

        // Throw 404 exception when application not found
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", [
                'The page you requested was not found',
                ' - controller "ControllerNotFound" not found',
            ]),
            404
        );
        $this->app->dispatch('ControllerNotFound');
    }

    public function testNestedController()
    {
        $result = $this->app->dispatch('admin/index');

        $this->assertInstanceOf('\Wei\Res', $result);

        $this->expectOutputString('admin.index');
    }

    public function testNestedControllerNotFound()
    {
        $result = $this->app->dispatch('Controller\Admin', 'index', [], false);

        $this->assertIsArray($result);

        $this->assertEquals([
            'controllers' => [
                'Controller\Admin' => [
                    'WeiTest\Fixtures\App\Controller\Controller\Admin',
                ],
            ],
        ], $result);
    }

    public function testActionNotFound()
    {
        $result = $this->app->dispatch('example', 'ActionNotFound', [], false);

        $this->assertEquals([
            'actions' => [
                'ActionNotFound' => [
                    'example' => [
                        'WeiTest\Fixtures\App\Controller\Example',
                    ],
                ],
            ],
        ], $result);
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

        $this->assertInstanceOf('\Wei\Res', $result);
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

        $this->app->dispatch('controller', 'dispatchBreak', [], false);
    }

    public function testForwardAction()
    {
        $this->expectOutputString('target');

        $this->req->setPathInfo('example/forwardAction');
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

        $this->assertInstanceOf('\Wei\Res', $response);

        $this->assertEquals('forwardParamsAction', $response->getContent());
    }

    public function testNestedControllerView()
    {
        $this->expectOutputString('value');

        $result = $this->app->dispatch('admin/index', 'view');

        $this->assertInstanceOf('\Wei\Res', $result);
    }

    /**
     * @dataProvider providerForActionReturnValue
     * @param mixed $action
     * @param mixed $output
     */
    public function testActionReturnValue($action, $output)
    {
        $this->expectOutputString($output);

        $this->app->dispatch('example', $action);
    }

    public function providerForActionReturnValue()
    {
        return [
            [
                'returnInt',
                '123',
            ],
            [
                'returnNull',
                '',
            ],
            [
                'returnFloat',
                '1.1',
            ],
            [
                'returnTrue',
                '1',
            ],
            [
                'returnFalse',
                '',
            ],
        ];
    }

    public function testActionParameters()
    {
        $this->expectOutputString('1');

        $this->app->dispatch('example', 'parameter', ['id' => '1']);
    }

    public function testActionIsProtected()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", [
                'The page you requested was not found',
                ' - method "showAction" not found in controller "example"',
            ]),
            '404'
        );

        $this->req->setPathInfo('example/protect');
        $this->app();
    }

    public function testActionNameCaseSensitive()
    {
        $this->expectOutputString('caseInsensitiveAction');

        $this->req->setPathInfo('example/caseInsensitive');
        $this->app();
    }

    public function testActionNameCaseSensitiveException()
    {
        $this->setExpectedException(
            'RuntimeException',
            implode("\n", [
                'The page you requested was not found',
                ' - method "showAction" not found in controller "example"',
            ]),
            '404'
        );

        $this->req->setPathInfo('example/caseinsensitive');
        $this->app();
    }

    public function testNamespace()
    {
        $this->assertEquals('', $this->app->getNamespace());

        $this->req->set([
            'namespace' => 'test',
        ]);
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
            implode("\n", [
                'The page you requested was not found',
                ' - controller "notFound" not found (class "WeiTest\Fixtures\App\Controller\NotFound")',
            ]),
            404
        );

        $this->req->set('debug-detail', '1');
        $this->app->dispatch('notFound');
    }

    public function testDebugDetailMessageForAction()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage(<<<'MSG'
The page you requested was not found
 - method "notFoundAction" not found in controller "example" (class "WeiTest\Fixtures\App\Controller\Example")
MSG
        );

        $this->req->set('debug-detail', '1');
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

        $this->req->set(['before' => 0, 'after' => 0]);
        $this->app->dispatch('callback');

        $this->assertEquals(1, $this->req['before']);
        $this->assertEquals(1, $this->req['after']);
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

    public function testRemoveMiddleware()
    {
        $controller = new Middleware(['wei' => $this->wei]);

        $controller->middleware(All::class);
        $this->assertArrayHasKey(All::class, $controller->getMiddleware());

        $controller->removeMiddleware(All::class);
        $this->assertArrayNotHasKey(All::class, $controller->getMiddleware());
    }
}
