<?php

namespace WeiTest;

/**
 * @internal
 */
final class PhpErrorTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function setUp(): void
    {
        if (!class_exists('\php_error\ErrorHandler')) {
            $this->markTestSkipped('joseph-lenton/php-error is required');
            return;
        }

        // Avoid "Undefined index: REQUEST_URI" error
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'localhost';

        parent::setUp();
    }

    public function testInvoker()
    {
        $this->assertInstanceOf('\php_error\ErrorHandler', $this->phpError());
    }
}
