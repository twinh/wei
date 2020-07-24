<?php

namespace WeiTest
{
    /**
     * @internal
     */
    final class ResponseTest extends TestCase
    {
        /**
         * @var \Wei\Response
         */
        protected $object;

        public function testFromCreateToSend()
        {
            $response = $this->object;

            // Prepare
            $response->setStatusCode(200);
            $response->setContent('body');
            $response->setHeader([
                'Key' => 'Value',
                'Key1' => 'Value1',
            ]);
            $response->setCookie('key', 'value');

            // Send
            $output = $this->getOutput($response);

            $this->assertEquals('body', $output);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals('body', $response->getContent());
            $this->assertTrue($response->isHeaderSent());
            $this->assertEquals('value', $response->getCookie('key'));
        }

        public function testSend()
        {
            $response = $this->object;

            $this->assertEquals('content', $this->getOutput($response, 'content', 304));
            $this->assertEquals(304, $response->getStatusCode());
        }

        public function testToString()
        {
            $response = $this->object;

            $response->setHeader([
                'Key' => 'Value',
                'Key1' => 'Value1',
            ]);

            $this->assertEquals("HTTP/1.1 200 OK\r\nKey: Value\r\nKey1: Value1\r\n\r\n", (string) $response);
        }

        public function getOutput(\Wei\Response $response, $content = null, $statusCode = null)
        {
            ob_start();
            // Equals to $response->send($content, $statusCode);
            $response($content, $statusCode);
            return ob_get_clean();
        }

        public function testVersion()
        {
            $response = $this->object;

            $response->setVersion('1.1');
            $this->assertEquals('1.1', $response->getVersion());

            $response->setVersion('1.0');
            $this->assertEquals('1.0', $response->getVersion());
        }

        public function testSetStatusCode()
        {
            $response = $this->object;

            $response->setStatusCode(200, 'Right!');

            $parts = explode("\r\n", $response);

            $this->assertStringContainsString('HTTP/1.1 200 Right!', $parts[0]);
        }

        public function testSendHeader()
        {
            $response = $this->object;

            $response->setHeader('key', 'value');

            $this->assertTrue($response->sendHeader());
            $this->assertFalse($response->sendHeader());
        }

        public function testDownload()
        {
            $file = __DIR__ . '/Fixtures/views/content.php';

            ob_start();
            $this->response->download($file);
            $content = ob_get_clean();

            $this->assertSame(file_get_contents($file), $content);
        }

        public function testFileNotFoundException()
        {
            $this->expectException(\RuntimeException::class);

            $this->response->download('not found file');
        }

        public function testFlush()
        {
            $this->assertInstanceOf('\Wei\Response', $this->response->flush());

            // FIXME https://github.com/facebook/hhvm/issues/1284
            if (!defined('HHVM_VERSION')) {
                $this->assertEquals('1', ini_get('implicit_flush'));
            }

            $this->assertEquals(0, ob_get_level());

            /**
             * @link https://github.com/symfony/symfony/issues/2531
             * @link https://github.com/sebastianbergmann/phpunit/issues/390
             */
            if (0 === ob_get_level()) {
                ob_start();
            }
        }

        public function testJson()
        {
            $this->expectOutputString('{"code":-1,"message":"error"}');

            $this->object->json(['code' => -1, 'message' => 'error'])->send();

            $this->assertEquals('application/json', $this->object->getHeader('Content-Type'));
        }

        public function testJsonp()
        {
            $this->request->set('callback', 'callback');

            $this->expectOutputString('callback({"code":-1,"message":"error"})');

            $this->object->jsonp(['code' => -1, 'message' => 'error'])->send();

            $this->assertEquals('application/javascript', $this->object->getHeader('Content-Type'));
        }

        public function testJsonpCallbackNameWithDot()
        {
            $this->request->set('callback', 'call.back');

            $this->expectOutputString('call.back({"code":-1,"message":"error"})');

            $this->object->jsonp(['code' => -1, 'message' => 'error'])->send();

            $this->assertEquals('application/javascript', $this->object->getHeader('Content-Type'));
        }

        public function testSendArrayAsJson()
        {
            $this->expectOutputString('{"code":1,"message":"success"}');

            $this->object->send(['code' => 1, 'message' => 'success']);

            $this->assertEquals('application/json', $this->object->getHeader('Content-Type'));
        }

        public function testCustomView()
        {
            $this->expectOutputString('redirect');

            $this->response->setRedirectView(__DIR__ . '/Fixtures/redirect.php');

            $this->response->redirect()->send();
        }

        public function testViewNotFound()
        {
            $this->expectException(\RuntimeException::class);

            $this->response->setRedirectView('not found');
        }

        public function testRedirectByHeader()
        {
            $this->expectOutputRegex('/http:\/\/www\.google\.com/');

            $this->response->redirect('http://www.google.com')->send();

            $this->assertEquals('http://www.google.com', $this->response->getHeader('Location'));
        }

        public function testWait()
        {
            $this->expectOutputRegex('/content=\"5;url=http:\/\/www\.google\.com/');

            $this->response->redirect('http://www.google.com', 302, ['redirectWait' => 5])->send();
        }
    }
}

namespace
{
    if (!function_exists('apache_setenv')) {
        function apache_setenv()
        {
        }
    }
}
