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
        $response = new \Widget\Response(array(
            'widget' => $this->widget,
            'statusCode' => 200,
            'content' => 'body'
        ));
        
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('body', $response->getContent());
        $this->assertEquals('body', $this->getOutput($response));
    }
    
    public function testSend()
    {
        $response = $this->object;
        
        $this->assertEquals('content', $this->getOutput($response, 'content', 304));
        $this->assertEquals(304, $response->getStatusCode());
    }
    
    public function getOutput(\Widget\Response $response, $content = null, $statusCode = null)
    {
        ob_start();
        // Equals to $response->send($content, $statusCode);
        $response($content, $statusCode);
        return ob_get_clean();
    }
}