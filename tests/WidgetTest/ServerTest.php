<?php

namespace WidgetTest;

class RequestTest extends TestCase
{
    public function testGetHeaders()
    {
        $this->server->fromArray(array(
            'REDIRECT_STATUS' => '200',
            'HTTP_HOST' => 'web',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.5 Safari/537.22',
            'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8',
            'HTTP_ACCEPT_CHARSET' => 'UTF-8,*;q=0.5',
            'PATH' => '/usr/local/bin:/usr/bin:/bin',
            'SERVER_SIGNATURE' => '<address>Apache/2.2.22 (Ubuntu) Server at web Port 80</address>',
            'SERVER_SOFTWARE' => 'Apache/2.2.22 (Ubuntu)',
            'SERVER_NAME' => 'web',
            'SERVER_ADDR' => '192.168.25.2',
            'SERVER_PORT' => '80',
            'REMOTE_ADDR' => '192.168.25.1',
            'DOCUMENT_ROOT' => '/mnt/hgfs/e/web',
            'SERVER_ADMIN' => '[no address given]',
            'SCRIPT_FILENAME' => '/mnt/hgfs/e/web/blog/index.php',
            'REMOTE_PORT' => '61943',
            'REDIRECT_QUERY_STRING' => 'string',
            'REDIRECT_URL' => '/blog/hello',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'string',
            'REQUEST_URI' => '/blog/hello?string',
            'SCRIPT_NAME' => '/blog/index.php',
            'PHP_SELF' => '/blog/index.php',
            'REQUEST_TIME' => 1364916034,
        ));
        
        $this->assertEquals(array (
            'HOST' => 'web',
            'CONNECTION' => 'keep-alive',
            'CACHE_CONTROL' => 'max-age=0',
            'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.5 Safari/537.22',
            'ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8',
            'ACCEPT_CHARSET' => 'UTF-8,*;q=0.5',
          ), $this->server->getHeaders());
    }
}