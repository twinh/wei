<?php

namespace WidgetTest;

class ResponseTest extends TestCase
{
    /**
     * @var \Widget\Response
     */
    protected $object;

    public function testFromCreateToSend()
    {
        $response = $this->object;

        // Prepare
        $response->setStatusCode(200);
        $response->setContent('body');
        $response->setHeader(array(
            'Key' => 'Value',
            'Key1' => 'Value1'
        ));
        $response->setCookie('key', 'value');

        // Send
        $output = $this->getOutput($response);

        $this->assertEquals('body', $output);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('body', $response->getContent());
        $this->assertTrue($response->isHeaderSent());
    }

    public function testSend()
    {
        $response = $this->object;

        $this->assertFalse($response->isSent());
        $this->assertEquals('content', $this->getOutput($response, 'content', 304));
        $this->assertEquals(304, $response->getStatusCode());
        $this->assertTrue($response->isSent());
    }

    public function testToString()
    {
        $response = $this->object;

        $response->header->set(array(
            'Key' => 'Value',
            'Key1' => 'Value1'
        ));

        $this->assertEquals("HTTP/1.1 200 OK\r\nKey: Value\r\nKey1: Value1\r\n\r\n", (string)$response);
    }

    public function getOutput(\Widget\Response $response, $content = null, $statusCode = null)
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

        $this->assertContains('HTTP/1.1 200 Right!', $parts[0]);
    }
}