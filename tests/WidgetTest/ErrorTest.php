<?php

namespace WidgetTest;

/**
 * It's hard to test error, some of the tests is use for code coverage only
 *
 * @property \Widget\Error $error The error widget
 */
class ErrorTest extends TestCase
{
    protected function tearDown()
    {
        $this->logger->clean();
        parent::tearDown();
    }

    public function testFatalException()
    {
        $test = $this;
        $this->error->fatal(function($exception) use($test) {
            $test->assertEquals('Class not found', $exception->getMessage());
        });
        $exception = new \ErrorException('Class not found', 0, 0, __FILE__, __LINE__);
        $this->error->triggerHandler('fatal', $exception);
    }

    public function createErrorView($message, $code = 0)
    {
        $exception = new \Exception($message, $code);

        $this->error->handleException($exception);
    }

    public function testException()
    {
        $this->expectOutputRegex('/test exception/');

        $this->createErrorView('test exception');
    }

    public function testErrorHandler()
    {
        error_reporting(0);

        // Cover \Widget\Error::handleError
        $array['key'];

        error_reporting(-1);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testErrorToException()
    {
        $array['key'];
    }

    public function testHandleException()
    {
        // Output error like Method "Widget\Widget->debug" or widget "debug" (class "Widget\Debug") not found, called in file ...
        $this->expectOutputRegex('/Critical/');

        // Change logger to widget to make error in handleException
        $this->error->logger = $this->widget;

        $exception = new \Exception();

        $this->error->handleException($exception);
    }
}