<?php

namespace WeiTest;

/**
 * @property \Wei\App $app The application service
 * @property \Wei\Request $request
 */
class AppTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->view->setDirs(__DIR__ . '/App/views');

        $this->app->setOption('controllerFormat', 'WeiTest\App\%controller%');
    }

    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testBaseApp()
    {
        // WeiTest\App\Controller\Test::testAction
        $this->app->dispatch('test', 'test');

        $this->expectOutputString('test');
    }

    public function testControllerNotFound()
    {
        $result = $this->app->dispatch('ControllerNotFound');

        $this->assertInternalType('array', $result);

        $this->assertEquals(array('classes' => 'WeiTest\App\ControllerNotFound'), $result);
    }

    public function testNestedController()
    {
        $result = $this->app->dispatch('admin/index');

        $this->assertInstanceOf('\Wei\Response', $result);

        $this->expectOutputString('admin.index');
    }

    public function testNestedControllerNotFound()
    {
        $result = $this->app->dispatch('Controller\Admin');

        $this->assertInternalType('array', $result);

        $this->assertEquals(array('classes' => 'WeiTest\App\Controller\Admin'), $result);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage The page you requested was not found - action method "ActionNotFound" not found in controller "test" (class "WeiTest\App\Test")
     */
    public function testActionNotFound()
    {
        $this->app->setAction('ActionNotFound');

        $this->app();
    }

    public function testActionReturnArrayAsViewParameter()
    {
        // WeiTest\App\Controller\TestController::returnArrayAction
        $this->request->set(array(
            'controller' => 'test',
            'action' => 'returnArray'
        ));

        $this->expectOutputString('value');

        $this->app();
    }

    public function testActionReturnResponseWei()
    {
        // WeiTest\App\Controller\TestController::returnResponseAction
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
        // WeiTest\App\Controller\TestController::returnUnexpectedTypeAction
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

    public function testDispatchBreakInConstructor()
    {
        $this->request->set('controller', 'dispatchBreak');

        $this->expectOutputString('');

        $this->app();
    }

    public function testGetControllerInstance()
    {
        $this->assertFalse($this->app->getControllerInstance('../invalid/controller', 'index'));

        $controller = $this->app->getControllerInstance('test', 'index');
        $this->assertInstanceOf('WeiTest\App\Test', $controller);

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
        $this->expectOutputString('index');

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'forwardController'
        ));

        $this->app();
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

        $this->request->set(array(
            'controller' => 'test',
            'action' => $action
        ));

        $this->app();
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

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'parameter',
            'id' => '1'
        ));

        $this->app();
    }

    public function testActionStartWithUnderscore()
    {
        $this->setExpectedException(
            'RuntimeException',
            'The page you requested was not found - action method "_action" not found in controller "test" (class "WeiTest\App\Test")',
            '404'
        );

        $this->request->set(array(
            'controller' => 'test',
            'action' => '_action',
        ));

        $this->app();
    }

    public function testActionIsProtected()
    {
        $this->setExpectedException(
            'RuntimeException',
            'The page you requested was not found - action method "protect" not found in controller "test" (class "WeiTest\App\Test")',
            '404'
        );

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'protect',
        ));

        $this->app();
    }

    public function testActionNameCaseSensitive()
    {
        $this->expectOutputString('caseInsensitive');

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'caseInsensitive',
        ));

        $this->app();
    }

    public function testActionNameCaseSensitiveException()
    {
        $this->setExpectedException(
            'RuntimeException',
            'The page you requested was not found - action method "caseinsensitive" not found in controller "test" (class "WeiTest\App\Test")',
            '404'
        );

        $this->request->set(array(
            'controller' => 'test',
            'action' => 'caseinsensitive',
        ));

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
}
