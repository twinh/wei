<?php

namespace WidgetTest;

/**
 * @property \Widget\App $app The application widget
 */
class AppTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->view->setDirs(__DIR__ . '/AppTest/views');

        $this->app
            ->setOption('namespace', 'WidgetTest\AppTest');
    }

    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testBaseApp()
    {
        // WidgetTest\AppTest\Controller\Test::testAction
        $this->request->set(array(
            'controller' => 'test',
            'action' => 'test'
        ));

        $this->app();

        $this->expectOutputString('test');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - controller "ControllerNotFound" (class "WidgetTest\AppTest\ControllerNotFound") not found
     */
    public function testControllerNotFound()
    {
        $this->app->setController('ControllerNotFound');

        $this->app();
    }

    public function testNestedController()
    {
        $this->app->setController('admin/index');

        $this->app->setAction('index');

        $this->app();

        $this->expectOutputString('admin.index');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - controller "Controller\Admin" (class "WidgetTest\AppTest\Controller\Admin") not found
     */
    public function testNestedControllerNotFound()
    {
        $this->app->setController('Controller\Admin');

        $this->app();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - action method "ActionNotFoundAction" not found in controller "test" (class "WidgetTest\AppTest\Test")
     */
    public function testActionNotFound()
    {
        $this->app->setAction('ActionNotFound');

        $this->app();
    }

    public function testActionReturnArrayAsViewParameter()
    {
        // WidgetTest\AppTest\Controller\TestController::returnArrayAction
        $this->request->set(array(
            'controller' => 'test',
            'action' => 'returnArray'
        ));

        $this->expectOutputString('value');

        $this->app();
    }

    public function testActionReturnResponseWidget()
    {
        // WidgetTest\AppTest\Controller\TestController::returnResponseAction
        $this->request->set(array(
            'controller' => 'test',
            'action' => 'returnResponse'
        ));

        $this->expectOutputString('response content');

        $this->app();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testActionReturnUnexpectedType()
    {
        // WidgetTest\AppTest\Controller\TestController::returnUnexpectedTypeAction
        $this->request->set(array(
            'controller' => 'test',
            'action' => 'returnUnexpectedType'
        ));

        $this->app();
    }

    public function testDispatchBreak()
    {
        $this->request->set('action', 'dispatchBreak');

        $this->expectOutputString('stop');

        $this->app();
    }

    public function testGetControllerInstance()
    {
        $this->assertFalse($this->app->getControllerInstance('../invalid/controller', 'index'));

        $controller = $this->app->getControllerInstance('test', 'index');
        $this->assertInstanceOf('WidgetTest\AppTest\Test', $controller);

        $controller2 = $this->app->getControllerInstance('test', 'index');
        $this->assertSame($controller2, $controller);
    }

    public function testForwardAction()
    {
        $this->expectOutputString('target');

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'forwardAction'
        ));

        $this->app();
    }

    public function testForwardController()
    {
        $this->expectOutputString('target');

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'forwardController'
        ));

        $this->app();
    }

    public function testNestedControllerView()
    {
        $this->expectOutputString('value');

        $this->request->set(array(
            'controller' => 'admin/index',
            'action' => 'view'
        ));

        $this->app();
    }
}