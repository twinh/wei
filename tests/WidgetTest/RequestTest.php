<?php

namespace WidgetTest;

class RequestTest extends TestCase
{
    /**
     * @var \Widget\Request
     */
    protected $object;

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

        $this->assertNull($widget->request->get('remove'));
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

            $this->assertTrue($request->{'is' . $method}());
            $this->assertTrue($request->isMethod($method));
        }
    }

    public function testAjax()
    {
        $this->request->setServer('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');

        $this->assertTrue($this->request->inAjax());

        $this->request->setServer('HTTP_X_REQUESTED_WITH', 'json');

        $this->assertFalse($this->request->inAjax());

        $servers = $this->request->getParameterReference('server');
        unset($servers['HTTP_X_REQUESTED_WITH']);

        $this->assertFalse($this->request->inAjax());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidParameterReference()
    {
        $this->request->getParameterReference('exception');
    }

    public function testGetIp()
    {
        $this->request->setServer('HTTP_X_FORWARDED_FOR', '1.2.3.4');
        $this->assertEquals('1.2.3.4', $this->request->getIp());

        $this->request->setServer('HTTP_X_FORWARDED_FOR', '1.2.3.4, 2.3.4.5');
        $this->assertEquals('1.2.3.4', $this->request->getIp());

        $servers = &$this->request->getParameterReference('server');
        unset($servers['HTTP_X_FORWARDED_FOR']);
        $servers['HTTP_CLIENT_IP'] = '8.8.8.8';
        $this->assertEquals('8.8.8.8', $this->request->getIp());

        $servers = $this->request->getParameterReference('server');
        unset($servers['HTTP_CLIENT_IP']);
        $servers['REMOTE_ADDR'] = '9.9.9.9';
        $this->assertEquals('9.9.9.9', $this->request->getIp());

        $this->request->setServer('HTTP_X_FORWARDED_FOR', 'invalid ip');
        $this->assertEquals('0.0.0.0', $this->request->getIp());
    }

    public function testGetScheme()
    {
        $this->request->setServer('HTTPS', 'on');
        $this->assertEquals('https', $this->request->getScheme());

        $this->request->setServer('HTTPS', '1');
        $this->assertEquals('https', $this->request->getScheme());

        $this->request->setServer('HTTPS', 'off');
        $this->assertEquals('http', $this->request->getScheme());

        $servers = $this->request->getParameterReference('server');
        unset($servers['HTTPS']);
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
        $this->request->setOption('servers', $server);

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
        $this->request->setOption('servers', $server);
        $this->assertEquals('a.test.com', $this->request->getHost());

        unset($server['HTTP_HOST']);
        $this->request->setOption('servers', $server);
        $this->assertEquals('test.com', $this->request->getHost());
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
                'https://test.com/index.php?query=string',
                'https://test.com/path',
            ),
            array(
                array(
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '8080',
                    'HTTP_HOST' => 'test.com',
                    'REQUEST_URI' => '/index.php?query=string'
                ),
                'https://test.com:8080/index.php?query=string',
                'https://test.com:8080/path'
            )
        );
    }

    /**
     * @dataProvider providerForGetUrl
     */
    public function testGetUrl($server, $url, $urlPath)
    {
        $this->request->setOption('servers', $server);

        $this->assertEquals($url, $this->request->getUrl());
        $this->assertEquals($urlPath, $this->request->getUrlFor('/path'));
    }

    public function testGetContent()
    {
        // Should be empty on not post request
        $this->assertEmpty($this->request->getContent());

        $this->request->setContent(__METHOD__);

        $this->assertEquals(__METHOD__, $this->request->getContent());
    }

    public function testToString()
    {
        $this->request->setOption('servers', array(
            'HTTPS' => 'on',
            'SERVER_PORT' => '8080',
            'HTTP_HOST' => 'test.com',
            'REQUEST_URI' => '/index.php?query=string',
            'SERVER_PROTOCOL' => 'HTTP/1.0',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => 'index.php'
        ));

        $this->assertEquals(
            "GET https://test.com:8080/index.php?query=string HTTP/1.0\r\nHost: test.com\r\n",
            (string)$this->request
        );
    }

    public function testPathInfo()
    {
        $this->request->setPathInfo('/blog');

        $this->assertEquals('/blog', $this->request->getPathInfo());
    }

    public function testEmptyPort()
    {
        $request = new \Widget\Request(array(
            'widget' => $this->widget,
            'fromGlobal' => false,
            'servers' => array(
                'HTTP_HOST' => 'test.com'
            ),
        ));

        $this->assertEquals('http://test.com/', $request->getUrl());
    }

    /**
     * @link https://github.com/twinh/widget/issues/54
     */
    public function testErrorParameterTypeWhenFromGlobalIsFalse()
    {
        $request = new \Widget\Request(array(
            'fromGlobal' => false
        ));

        foreach (array('get', 'cookie', 'server', 'file') as $option) {
            $this->assertInternalType('array', $request->getParameterReference($option));
        }
    }

    public function testGetHeaders()
    {
        $this->request->setOption('servers', array(
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
          ), $this->request->getHeaders());
    }

    public function testInvoker()
    {
        $request = $this->object;

        $request->fromArray(array(
            'string' => 'value',
            1 => 2
        ));

        $this->assertEquals('value', $request('string'));

        $this->assertEquals('custom', $request('no this key', 'custom'));
    }

    public function testCount()
    {
        $widget = $this->object;

        $widget->fromArray(range(1, 10));

        $this->assertCount(10, $widget);
    }

    public function testFromArray() {
        $widget = $this->object;

        $widget['key2'] = 'value2';

        $widget->fromArray(array(
            'key1' => 'value1',
            'key2' => 'value changed',
        ));

        $this->assertEquals('value1', $widget['key1']);

        $this->assertEquals('value changed', $widget['key2']);
    }

    public function testToArray()
    {
        $widget = $this->object;

        $widget->fromArray(array(
            'key' => 'other value',
        ));

        $arr = $widget->toArray();

        $this->assertContains('other value', $arr);
    }

    public function testArrayAsSetParameter()
    {
        $array = array(
            'key' => 'value',
            'key1' => 'value1'
        );

        $this->request->set($array);

        $this->assertIsSubset($array, $this->request->toArray());
    }

    public function testOffsetExists() {
        $widget = $this->object;

        $widget['key'] = 'value';

        $this->assertTrue(isset($widget['key']));
    }

    public function testOffsetGet() {
        $widget = $this->object;

        $widget['key'] = 'value1';

        $this->assertEquals('value1', $widget['key']);
    }

    public function testOffsetUnset() {
        $widget = $this->object;

        unset($widget['key']);

        $this->assertNull($widget['key']);
    }

    public function createParameterObject($type, $class)
    {
        // create request widget from custom parameter
        $request = new \Widget\Request(array(
            'widget' => $this->widget,
            'fromGlobal' => false,
            $type => array(
                'key' => 'value',
                'key2' => 'value2',
                'int' => '5',
                'array' => array(
                    'item' => 'value'
                )
            )
        ));
        return $request;
    }

    public function testGetter()
    {
        $parameters = array(
            'data' => 'request'
        );

        foreach ($parameters as $type => $class) {
            $parameter = $this->createParameterObject($type, $class);

            $this->assertEquals('value', $parameter->get('key'));

            $this->assertEquals(5, $parameter->getInt('int'));

            $this->assertEquals('', $parameter->get('notFound'));

            // int => 5, not in specified array
            $this->assertEquals('firstValue', $parameter->getInArray('int', array(
                'firstKey' => 'firstValue',
                'secondKey' => 'secondValue'
            )));

            // int => 5
            $this->assertEquals(6, $parameter->getInt('int', 6));

            $this->assertEquals(6, $parameter->getInt('int', 6, 10));

            $this->assertEquals(4, $parameter->getInt('int', 1, 4));
        }
    }
}