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

        $this->app
            ->setOption('controllerFormat', '%module%\Controller\\%controller%Controller')
            // Change avaiable modules
            ->setOption('modules', array('WidgetTest\AppTest'))
            // Set default module
            ->setModule('WidgetTest\AppTest');
    }

    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testBaseApp()
    {
        // WidgetTest\AppTest\Controller\TestController::testAction
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
     * @expectedExceptionMessage The page you requested was not found - module "ModuleNotFound" is not available
     */
    public function testModuleNotFound()
    {
        $this->app->setModule('ModuleNotFound');

        $this->app();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - controller "ControllerNotFound" not found in module "WidgetTest\AppTest"
     */
    public function testControllerNotFound()
    {
        $this->app->setController('ControllerNotFound');

        $this->app();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - action "ActionNotFound" not found in controller "WidgetTest\AppTest\Controller\TestController"
     */
    public function testActionNotFound()
    {
        $this->app->setAction('ActionNotFound');

        $this->app();
    }

    public function testActionReturnArrayAsViewParameter()
    {
        $this->view->setDirs(__DIR__ . '/AppTest/views');

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

    public function testGetModuleFromRequest()
    {
        $this->app->setModule(null);
        $this->request->set('module', 'test');

        $this->assertEquals('test', $this->app->getModule());
    }

    public function testDispatchBreak()
    {
        $this->request->set('action', 'dispatchBreak');

        $this->expectOutputString('stop');

        $this->app();
    }

    public function testGetControllerInstance()
    {
        $this->assertFalse($this->app->getControllerInstance('Module', '../invalid/controller'));

        $controller = $this->app->getControllerInstance('WidgetTest\AppTest', 'test');
        $this->assertInstanceOf('WidgetTest\AppTest\Controller\TestController', $controller);

        $controller2 = $this->app->getControllerInstance('WidgetTest\AppTest', 'test');
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
}