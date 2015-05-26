<?php

namespace WeiTest;

/**
 * It's hard to test error, some of the tests is use for code coverage only
 *
 * @property \Wei\Error $error The error wei
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
        $this->error->fatal(function ($exception) use ($test) {
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

        // Cover \Wei\Error::handleError
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
        // Output error like Method "Wei\Wei->debug" or wei "debug" (class "Wei\Debug") not found, called in file ...
        $this->expectOutputRegex('/Critical/');

        // Change logger to wei to make error in handleException
        $this->error->logger = $this->wei;

        $exception = new \Exception();

        $this->error->handleException($exception);
    }

    public function testCustomErrorMessage()
    {
        $this->error->setOption(array(
            'message' => 'Oops!',
            'detail' => 'Something wrong'
        ));

        $exception = new \Exception('');

        ob_start();
        $this->error->handleException($exception);
        $content = ob_get_clean();

        $this->assertContains('Oops!', $content);
        $this->assertContains('Something wrong', $content);
    }
}
