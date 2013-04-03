<?php

namespace WidgetTest;

class RequestTest extends TestCase
{
    public function testInvoke()
    {
        $widget = $this->object;

        $name = $widget->request('name');
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

        $this->assertEquals($name, $source);

        $default = 'default';
        $name2 = $widget->request('name', $default);
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : $default;

        $this->assertEquals($name2, $default);
    }

    public function testSet()
    {
        $widget = $this->object;

        $widget->set('key', 'value');

        $this->assertEquals('value', $widget->request('key'), 'string param');

        $widget->fromArray(array(
            'key1' => 'value1',
            'key2' => 'value2',
        ));

        $this->assertEquals('value2', $widget->request('key2'), 'array param');
    }

    public function testRemove()
    {
        $widget = $this->object;

        $widget->set('remove', 'just a moment');

        $this->assertEquals('just a moment', $widget->request('remove'));

        $widget->remove('remove');

        $this->assertNull($widget->request('remove'));
    }
    
    public function testMethod()
    {
        foreach (array('GET', 'POST') as $method) {
            $this->widget->remove('request');
            $this->widget->remove('server');
            $request = new \Widget\Request(array(
                'widget' => $this->widget,
                'fromGlobal' => false,
                'servers' => array(
                    'REQUEST_METHOD' => $method
                )
            ));
            $this->widget->set('request', $request);
            
            $this->assertTrue($request->{'in' . $method}());
            $this->assertTrue($request->inMethod($method));

            $method = strtolower($method);
            $method[0] = strtoupper($method[0]);
            $this->{'in' . $method}->setOption('request', $request);
            $this->assertTrue($this->{'in' . $method}());
        }
        
        $this->request->setMethod('PUT');
        $this->assertTrue($this->request->inMethod('PUT'));
    }
    
    public function testAjax()
    {
        $this->server->set('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');
        
        $this->assertTrue($this->inAjax());
        
        $this->server->set('HTTP_X_REQUESTED_WITH', 'json');
        
        $this->assertFalse($this->inAjax());
        
        $this->server->remove('HTTP_X_REQUESTED_WITH');
        
        $this->assertFalse($this->inAjax());
    }
    
    /**
     * @expectedException \Widget\Exception\InvalidArgumentException
     */
    public function testInvalidParameterReference()
    {
        $this->request->getParameterReference('exception');
    }
    
    public function testGetIp()
    {
        $this->server->set('HTTP_X_FORWARDED_FOR', '1.2.3.4');
        $this->assertEquals('1.2.3.4', $this->request->getIp());
        
        $this->server->set('HTTP_X_FORWARDED_FOR', '1.2.3.4, 2.3.4.5');
        $this->assertEquals('1.2.3.4', $this->request->getIp());
        
        $this->server->remove('HTTP_X_FORWARDED_FOR');
        $this->server->set('HTTP_CLIENT_IP', '8.8.8.8');
        $this->assertEquals('8.8.8.8', $this->request->getIp());
        
        $this->server->remove('HTTP_CLIENT_IP');
        $this->server->set('REMOTE_ADDR', '9.9.9.9');
        $this->assertEquals('9.9.9.9', $this->request->getIp());
        
        $this->server->set('HTTP_X_FORWARDED_FOR', 'invalid ip');
        $this->assertEquals('0.0.0.0', $this->request->getIp());
    }
    
    public function testGetScheme()
    {
        $this->server->set('HTTPS', 'on');
        $this->assertEquals('https', $this->request->getScheme());
        
        $this->server->set('HTTPS', '1');
        $this->assertEquals('https', $this->request->getScheme());
        
        $this->server->set('HTTPS', 'off');
        $this->assertEquals('http', $this->request->getScheme());
        
        $this->server->remove('HTTPS', '1');
        $this->assertEquals('http', $this->request->getScheme());
    }
    
    /**
     * @link https://github.com/zendframework/zf2/blob/master/tests/ZendTest/Http/PhpEnvironment/RequestTest.php
     * 
     * Data provider for testing base URL and path detection.
     */
    public function baseUrlAndPathProvider()
    {
        return array(
            array(
                array(
                    'REQUEST_URI'     => '/index.php/news/3?var1=val1&var2=val2',
                    'QUERY_URI'       => 'var1=val1&var2=val2',
                    'SCRIPT_NAME'     => '/index.php',
                    'PHP_SELF'        => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/public/index.php/news/3?var1=val1&var2=val2',
                    'QUERY_URI'       => 'var1=val1&var2=val2',
                    'SCRIPT_NAME'     => '/public/index.php',
                    'PHP_SELF'        => '/public/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/public/index.php',
                ),
                '/public/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/index.php/news/3?var1=val1&var2=val2',
                    'SCRIPT_NAME'     => '/home.php',
                    'PHP_SELF'        => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'      => '/index.php/news/3?var1=val1&var2=val2',
                    'SCRIPT_NAME'      => '/home.php',
                    'PHP_SELF'         => '/home.php',
                    'ORIG_SCRIPT_NAME' => '/index.php',
                    'SCRIPT_FILENAME'  => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF'        => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'HTTP_X_REWRITE_URL' => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF'           => '/index.php/news/3',
                    'SCRIPT_FILENAME'    => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            // IIS 7.0 or later with ISAPI_Rewrite
            array(
                array(
                    'HTTP_X_ORIGINAL_URL'   => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF'              => '/index.php/news/3',
                    'SCRIPT_FILENAME'       => '/var/web/html/index.php',
                ),
                '',
                '/'
            ),
            array(
                array(
                    'IIS_WasUrlRewritten'   => '1',
                    'UNENCODED_URL'         => '/index.php/news/3?var1=val1&var2=val2',
                    'HTTP_X_ORIGINAL_URL'   => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF'              => '/index.php/news/3',
                    'SCRIPT_FILENAME'       => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'ORIG_PATH_INFO'  => '/index.php/news/3',
                    'QUERY_STRING'    => 'var1=val1&var2=val2',
                    'PHP_SELF'        => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/article/archive?foo=index.php',
                    'QUERY_STRING'    => 'foo=index.php',
                    'SCRIPT_FILENAME' => '/var/www/zftests/index.php',
                ),
                '',
                '/article/archive'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/html/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF'        => '/html/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/html/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/dir/action',
                    'PHP_SELF'        => '/dir/index.php',
                    'SCRIPT_FILENAME' => '/var/web/dir/index.php',
                ),
                '/dir',
                '/action'
            ),
            array(
                array(
                    'SCRIPT_NAME'     => '/~username/public/index.php',
                    'REQUEST_URI'     => '/~username/public/',
                    'PHP_SELF'        => '/~username/public/index.php',
                    'SCRIPT_FILENAME' => '/Users/username/Sites/public/index.php',
                    'ORIG_SCRIPT_NAME'=> null
                ),
                '/~username/public',
                '/'
            ),
            // ZF2-206
            array(
                array(
                    'SCRIPT_NAME'     => '/zf2tut/index.php',
                    'REQUEST_URI'     => '/zf2tut/',
                    'PHP_SELF'        => '/zf2tut/index.php',
                    'SCRIPT_FILENAME' => 'c:/ZF2Tutorial/public/index.php',
                    'ORIG_SCRIPT_NAME'=> null
                ),
                '/zf2tut',
                '/'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/html/index.php/news/3?var1=val1&var2=/index.php',
                    'PHP_SELF'        => '/html/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/html/index.php',
                '/news/3'
            ),
            array(
                array(
                    'REQUEST_URI'     => '/html/index.php/news/index.php',
                    'PHP_SELF'        => '/html/index.php/news/index.php',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/html/index.php',
                '/news/index.php'
            ),

            //Test when url quert contains a full http url
            array(
                array(
                    'REQUEST_URI' => '/html/index.php?url=http://test.example.com/path/&foo=bar',
                    'PHP_SELF' => '/html/index.php',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ),
                '/html/index.php',
                '/'
            ),
            array(
                array(
                    'SCRIPT_FILENAME' => '/web/blog/index.php',
                    'SCRIPT_NAME' => '/blog/index.php',
                    'REQUEST_URI' => '/blog/hello?string',
                    'PHP_SELF' => '/blog/index.php'
                ),
                '/blog',
                '/hello'
            ),
            array(
                array(
                    'SCRIPT_FILENAME' => '/web/blog/index.php',
                    'SCRIPT_NAME' => '/blog/index.php',
                    'REQUEST_URI' => '/blog/hello?string',
                ),
                '',
                '/blog/hello'
            ),
            // cli php index.php
            array(
                array(
                    'PHP_SELF' => 'index.php',
                    'SCRIPT_NAME' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                    'DOCUMENT_ROOT' => '',
                ),
                '',
                '/'
            ),
            // NOTE The following data is for codecover only
            array(
                array(
                    'PHP_SELF' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                    'REQUEST_URI' => 'home/index.php?query=string'
                ),
                'home/index.php',
                '/'
            ),
            array(
                array(
                    'ORIG_SCRIPT_NAME' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                ),
                '',
                '/'
            ),
        );
    }

    /**
     * @dataProvider baseUrlAndPathProvider
     */
    public function testBasePathDetection(array $server, $baseUrl, $pathInfo)
    {
        $this->server->setOption('data', $server);

        $this->assertEquals($baseUrl,  $this->request->getBaseUrl());
        $this->assertEquals($pathInfo, $this->request->getPathInfo());
    }
    
    public function testGetHost()
    {
        $server = array(
            'HTTP_HOST' => 'a.test.com',
            'SERVER_NAME' => 'test.com',
            'REMOTE_ADDR' => '127.0.0.1'
        );
        $this->server->setOption('data', $server);
        $this->assertEquals('a.test.com', $this->request->getHost());
        
        unset($server['HTTP_HOST']);
        $this->server->setOption('data', $server);
        $this->assertEquals('test.com', $this->request->getHost());
        
        unset($server['SERVER_NAME']);
        $this->server->setOption('127.0.0.1', $server);
    }
    
    public function testSetRequestUri()
    {
        $this->request->setRequestUri('/blog');
        
        $this->assertEquals('/blog', $this->request->getRequestUri());
    }
    
    public function providerForGetUrl()
    {
        return array(
            array(
                array(
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '80',
                    'HTTP_HOST' => 'test.com',
                    'REQUEST_URI' => '/index.php?query=string'
                ),
                'https://test.com/index.php?query=string'
            ),
            array(
                array(
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '8080',
                    'HTTP_HOST' => 'test.com',
                    'REQUEST_URI' => '/index.php?query=string'
                ),
                'https://test.com:8080/index.php?query=string'
            )
        );
    }
    
    /**
     * @dataProvider providerForGetUrl
     */
    public function testGetUrl($server, $url)
    {
        $this->server->setOption('data', $server);
        
        $this->assertEquals($url, $this->request->getUrl());
    }
}