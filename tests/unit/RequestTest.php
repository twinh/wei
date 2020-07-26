<?php

namespace WeiTest;

use Wei\Request;

/**
 * @property Request $request
 *
 * @internal
 */
final class RequestTest extends TestCase
{
    /**
     * @var \Wei\Request
     */
    protected $object;

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function testInvoke()
    {
        $wei = $this->object;

        $name = $wei->request('name');
        // phpcs:disable MySource.PHP.GetRequestData.SuperglobalAccessedWithVar
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

        $this->assertEquals($name, $source);

        $default = 'default';
        $name2 = $wei->request('name', $default);
        // phpcs:disable MySource.PHP.GetRequestData.SuperglobalAccessedWithVar
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : $default;

        $this->assertEquals($name2, $default);
    }

    public function testSet()
    {
        $wei = $this->object;

        $wei->set('key', 'value');

        $this->assertEquals('value', $wei->request('key'), 'string param');

        $wei->fromArray([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $this->assertEquals('value2', $wei->request('key2'), 'array param');
    }

    public function testRemove()
    {
        $wei = $this->object;

        $wei->set('remove', 'just a moment');

        $this->assertEquals('just a moment', $wei->request('remove'));

        $wei->remove('remove');

        $this->assertNull($wei->request->get('remove'));
    }

    public function testMethod()
    {
        foreach (['GET', 'POST'] as $method) {
            $this->wei->remove('request');
            $this->wei->remove('server');
            $request = new \Wei\Request([
                'wei' => $this->wei,
                'fromGlobal' => false,
                'servers' => [
                    'REQUEST_METHOD' => $method,
                ],
            ]);
            $this->wei->set('request', $request);

            $this->assertTrue($request->{'is' . $method}());
            $this->assertTrue($request->isMethod($method));
        }
    }

    public function testAjax()
    {
        $this->request->setServer('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');

        $this->assertTrue($this->request->isAjax());

        $this->request->setServer('HTTP_X_REQUESTED_WITH', 'json');

        $this->assertFalse($this->request->isAjax());

        $servers = $this->request->getParameterReference('server');
        unset($servers['HTTP_X_REQUESTED_WITH']);

        $this->assertFalse($this->request->isAjax());
    }

    public function testInvalidParameterReference()
    {
        $this->expectException(\InvalidArgumentException::class);

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
     * Data provider for testing base URL and path detection.
     *
     * @link https://github.com/zendframework/zf2/blob/master/tests/ZendTest/Http/PhpEnvironment/RequestTest.php
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function baseUrlAndPathProvider()
    {
        return [
            [
                [
                    'REQUEST_URI' => '/index.php/news/3?var1=val1&var2=val2',
                    'QUERY_URI' => 'var1=val1&var2=val2',
                    'SCRIPT_NAME' => '/index.php',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/public/index.php/news/3?var1=val1&var2=val2',
                    'QUERY_URI' => 'var1=val1&var2=val2',
                    'SCRIPT_NAME' => '/public/index.php',
                    'PHP_SELF' => '/public/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/public/index.php',
                ],
                '/public/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/index.php/news/3?var1=val1&var2=val2',
                    'SCRIPT_NAME' => '/home.php',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/index.php/news/3?var1=val1&var2=val2',
                    'SCRIPT_NAME' => '/home.php',
                    'PHP_SELF' => '/home.php',
                    'ORIG_SCRIPT_NAME' => '/index.php',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'HTTP_X_REWRITE_URL' => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            // IIS 7.0 or later with ISAPI_Rewrite
            [
                [
                    'HTTP_X_ORIGINAL_URL' => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '',
                '/',
            ],
            [
                [
                    'IIS_WasUrlRewritten' => '1',
                    'UNENCODED_URL' => '/index.php/news/3?var1=val1&var2=val2',
                    'HTTP_X_ORIGINAL_URL' => '/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'ORIG_PATH_INFO' => '/index.php/news/3',
                    'QUERY_STRING' => 'var1=val1&var2=val2',
                    'PHP_SELF' => '/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/article/archive?foo=index.php',
                    'QUERY_STRING' => 'foo=index.php',
                    'SCRIPT_FILENAME' => '/var/www/zftests/index.php',
                ],
                '',
                '/article/archive',
            ],
            [
                [
                    'REQUEST_URI' => '/html/index.php/news/3?var1=val1&var2=val2',
                    'PHP_SELF' => '/html/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/html/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/dir/action',
                    'PHP_SELF' => '/dir/index.php',
                    'SCRIPT_FILENAME' => '/var/web/dir/index.php',
                ],
                '/dir',
                '/action',
            ],
            [
                [
                    'SCRIPT_NAME' => '/~username/public/index.php',
                    'REQUEST_URI' => '/~username/public/',
                    'PHP_SELF' => '/~username/public/index.php',
                    'SCRIPT_FILENAME' => '/Users/username/Sites/public/index.php',
                    'ORIG_SCRIPT_NAME' => null,
                ],
                '/~username/public',
                '/',
            ],
            // ZF2-206
            [
                [
                    'SCRIPT_NAME' => '/zf2tut/index.php',
                    'REQUEST_URI' => '/zf2tut/',
                    'PHP_SELF' => '/zf2tut/index.php',
                    'SCRIPT_FILENAME' => 'c:/ZF2Tutorial/public/index.php',
                    'ORIG_SCRIPT_NAME' => null,
                ],
                '/zf2tut',
                '/',
            ],
            [
                [
                    'REQUEST_URI' => '/html/index.php/news/3?var1=val1&var2=/index.php',
                    'PHP_SELF' => '/html/index.php/news/3',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/html/index.php',
                '/news/3',
            ],
            [
                [
                    'REQUEST_URI' => '/html/index.php/news/index.php',
                    'PHP_SELF' => '/html/index.php/news/index.php',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/html/index.php',
                '/news/index.php',
            ],

            //Test when url quert contains a full http url
            [
                [
                    'REQUEST_URI' => '/html/index.php?url=http://test.example.com/path/&foo=bar',
                    'PHP_SELF' => '/html/index.php',
                    'SCRIPT_FILENAME' => '/var/web/html/index.php',
                ],
                '/html/index.php',
                '/',
            ],
            [
                [
                    'SCRIPT_FILENAME' => '/web/blog/index.php',
                    'SCRIPT_NAME' => '/blog/index.php',
                    'REQUEST_URI' => '/blog/hello?string',
                    'PHP_SELF' => '/blog/index.php',
                ],
                '/blog',
                '/hello',
            ],
            [
                [
                    'SCRIPT_FILENAME' => '/web/blog/index.php',
                    'SCRIPT_NAME' => '/blog/index.php',
                    'REQUEST_URI' => '/blog/hello?string',
                ],
                '',
                '/blog/hello',
            ],
            // cli php index.php
            [
                [
                    'PHP_SELF' => 'index.php',
                    'SCRIPT_NAME' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                    'DOCUMENT_ROOT' => '',
                ],
                '',
                '/',
            ],
            // NOTE The following data is for codecover only
            [
                [
                    'PHP_SELF' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                    'REQUEST_URI' => 'home/index.php?query=string',
                ],
                'home/index.php',
                '/',
            ],
            [
                [
                    'ORIG_SCRIPT_NAME' => 'index.php',
                    'SCRIPT_FILENAME' => 'index.php',
                ],
                '',
                '/',
            ],
        ];
    }

    /**
     * @dataProvider baseUrlAndPathProvider
     * @param mixed $baseUrl
     * @param mixed $pathInfo
     */
    public function testBasePathDetection(array $server, $baseUrl, $pathInfo)
    {
        $this->request->setOption('servers', $server);

        $this->assertEquals($baseUrl, $this->request->getBaseUrl());
        $this->assertEquals($pathInfo, $this->request->getPathInfo());
    }

    public function testGetHost()
    {
        $server = [
            'HTTP_HOST' => 'a.test.com',
            'SERVER_NAME' => 'test.com',
            'REMOTE_ADDR' => '127.0.0.1',
        ];
        $this->request->setOption('servers', $server);
        $this->assertEquals('a.test.com', $this->request->getHost());

        unset($server['HTTP_HOST']);
        $this->request->setOption('servers', $server);
        $this->assertEquals('test.com', $this->request->getHost());
    }

    public function testGetHttpHostWithPort()
    {
        $server = [
            'HTTP_HOST' => '127.0.0.1:8080',
        ];
        $this->request->setOption('servers', $server);
        $this->assertEquals('127.0.0.1', $this->request->getHost());
    }

    public function testSetRequestUri()
    {
        $this->request->setRequestUri('/blog');

        $this->assertEquals('/blog', $this->request->getRequestUri());
    }

    public function providerForGetUrl()
    {
        return [
            [
                [
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '80',
                    'HTTP_HOST' => 'test.com',
                    'REQUEST_URI' => '/index.php?query=string',
                ],
                'https://test.com/index.php?query=string',
                'https://test.com/path',
            ],
            [
                [
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '8080',
                    'HTTP_HOST' => 'test.com',
                    'REQUEST_URI' => '/index.php?query=string',
                ],
                'https://test.com:8080/index.php?query=string',
                'https://test.com:8080/path',
            ],
        ];
    }

    /**
     * @dataProvider providerForGetUrl
     * @param mixed $server
     * @param mixed $url
     * @param mixed $urlPath
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
        $this->request->setOption('servers', [
            'HTTPS' => 'on',
            'SERVER_PORT' => '8080',
            'HTTP_HOST' => 'test.com',
            'REQUEST_URI' => '/index.php?query=string',
            'SERVER_PROTOCOL' => 'HTTP/1.0',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => 'index.php',
        ]);

        $this->assertEquals(
            "GET https://test.com:8080/index.php?query=string HTTP/1.0\r\nHost: test.com\r\n",
            (string) $this->request
        );
    }

    public function testPathInfo()
    {
        $this->request->setPathInfo('/blog');

        $this->assertEquals('/blog', $this->request->getPathInfo());
    }

    public function testEmptyPort()
    {
        $request = new \Wei\Request([
            'wei' => $this->wei,
            'fromGlobal' => false,
            'servers' => [
                'HTTP_HOST' => 'test.com',
            ],
        ]);

        $this->assertEquals('http://test.com/', $request->getUrl());
    }

    /**
     * @link https://github.com/twinh/wei/issues/54
     */
    public function testErrorParameterTypeWhenFromGlobalIsFalse()
    {
        $request = new \Wei\Request([
            'fromGlobal' => false,
        ]);

        foreach (['get', 'cookie', 'server', 'file'] as $option) {
            $this->assertIsArray($request->getParameterReference($option));
        }
    }

    public function testGetHeaders()
    {
        $this->request->setOption('servers', [
            'REDIRECT_STATUS' => '200',
            'HTTP_HOST' => 'web',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            // phpcs:disable Generic.Files.LineLength.TooLong
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
        ]);

        $this->assertEquals([
            'HOST' => 'web',
            'CONNECTION' => 'keep-alive',
            'CACHE_CONTROL' => 'max-age=0',
            'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            // phpcs:disable Generic.Files.LineLength.TooLong
            'USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.5 Safari/537.22',
            'ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8',
            'ACCEPT_CHARSET' => 'UTF-8,*;q=0.5',
        ], $this->request->getHeaders());
    }

    public function testInvoker()
    {
        $request = $this->object;

        $request->fromArray([
            'string' => 'value',
            1 => 2,
        ]);

        $this->assertEquals('value', $request('string'));

        $this->assertEquals('custom', $request('no this key', 'custom'));
    }

    public function testCount()
    {
        $wei = $this->object;

        $wei->fromArray(range(1, 10));

        $this->assertCount(10, $wei);
    }

    public function testFromArray()
    {
        $wei = $this->object;

        $wei['key2'] = 'value2';

        $wei->fromArray([
            'key1' => 'value1',
            'key2' => 'value changed',
        ]);

        $this->assertEquals('value1', $wei['key1']);

        $this->assertEquals('value changed', $wei['key2']);
    }

    public function testToArray()
    {
        $wei = $this->object;

        $wei->fromArray([
            'key' => 'other value',
        ]);

        $arr = $wei->toArray();

        $this->assertContains('other value', $arr);
    }

    public function testArrayAsSetParameter()
    {
        $array = [
            'key' => 'value',
            'key1' => 'value1',
        ];

        $this->request->set($array);

        $this->assertIsSubset($array, $this->request->toArray());
    }

    public function testOffsetExists()
    {
        $wei = $this->object;

        $wei['key'] = 'value';

        $this->assertTrue(isset($wei['key']));
    }

    public function testOffsetGet()
    {
        $wei = $this->object;

        $wei['key'] = 'value1';

        $this->assertEquals('value1', $wei['key']);
    }

    public function testOffsetUnset()
    {
        $wei = $this->object;

        unset($wei['key']);

        $this->assertNull($wei['key']);
    }

    public function createParameterObject($type, $class)
    {
        // create request wei from custom parameter
        $request = new \Wei\Request([
            'wei' => $this->wei,
            'fromGlobal' => false,
            $type => [
                'key' => 'value',
                'key2' => 'value2',
                'int' => '5',
                'array' => [
                    'item' => 'value',
                ],
            ],
        ]);
        return $request;
    }

    public function testGetter()
    {
        $parameters = [
            'data' => 'request',
        ];

        foreach ($parameters as $type => $class) {
            $parameter = $this->createParameterObject($type, $class);

            $this->assertEquals('value', $parameter->get('key'));

            $this->assertEquals(5, $parameter->getInt('int'));

            $this->assertEquals('', $parameter->get('notFound'));

            // int => 5, not in specified array
            $this->assertEquals('firstValue', $parameter->getInArray('int', [
                'firstKey' => 'firstValue',
                'secondKey' => 'secondValue',
            ]));

            // int => 5
            $this->assertEquals(6, $parameter->getInt('int', 6));

            $this->assertEquals(6, $parameter->getInt('int', 6, 10));

            $this->assertEquals(4, $parameter->getInt('int', 1, 4));
        }
    }

    public function testOverwriteAjax()
    {
        $request = new Request([
            'wei' => $this->wei,
            'data' => [],
        ]);
        $this->assertFalse($request->isAjax());

        $request = new Request([
            'wei' => $this->wei,
            'data' => [
                '_ajax' => true,
            ],
        ]);
        $this->assertFalse($request->isAjax());
    }

    public function testOverwriteMethod()
    {
        $request = new Request([
            'wei' => $this->wei,
            'fromGlobal' => false,
            'data' => [
                '_method' => 'PUT',
            ],
        ]);
        $this->assertTrue($request->isMethod('PUT'));
    }

    public function testArrayAccess()
    {
        $this->assertArrayBehaviour([]);
        $this->assertArrayBehaviour($this->request);
    }

    public function testAcceptJson()
    {
        $request = $this->request;
        $request->setServer('HTTP_ACCEPT', 'application/json, text/javascript, */*; q=0.01');
        $this->assertTrue($request->acceptJson());

        $request->setServer(
            'HTTP_ACCEPT',
            'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        );
        $this->assertFalse($request->acceptJson());
    }

    public function testAcceptJsonByOverwriteFormat()
    {
        $request = $this->request;
        $request->setServer(
            'HTTP_ACCEPT',
            'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        );
        $this->assertFalse($request->acceptJson());

        $request->set('_format', 'json');
        $this->assertTrue($request->acceptJson());
    }

    public function testIsFormat()
    {
        $request = $this->request;
        $request->set('_format', null);
        $request->setServer(
            'HTTP_ACCEPT',
            'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        );
        $this->assertFalse($request->acceptJson());

        $request->set('_format', 'xlsx');
        $this->assertTrue($request->isFormat('xlsx'));
    }

    public function testIsFormatJson()
    {
        $request = $this->request;
        $request->setServer('HTTP_ACCEPT', 'application/json, text/javascript, */*; q=0.01');
        $this->assertTrue($request->isFormat('json'));
    }

    /**
     * @dataProvider acceptProvider
     * @param mixed $mime
     * @param mixed $header
     * @param mixed $result
     */
    public function testAccept($mime, $header, $result)
    {
        $this->request->setServer('HTTP_ACCEPT', $header);
        $this->assertSame($result, $this->request->accept($mime));
    }

    public function acceptProvider()
    {
        return [
            [
                'application/json',
                'application/json, text/javascript, */*; q=0.01',
                true,
            ],
            [
                'text/javascript',
                'application/json, text/javascript, */*; q=0.01',
                false,
            ],
            [
                'text/html',
                'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                true,
            ],
            [
                'application/xml',
                'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                false,
            ],
            [
                'application/xml',
                '*/*',
                false,
            ],
        ];
    }

    public function testSetMethodShouldBeUppercase()
    {
        $this->request->setMethod('put');
        $this->assertEquals('PUT', $this->request->getMethod());
    }

    public function testExtraKeyInit()
    {
        $this->initExtraKey();

        $extraKeys = $this->request->getOption('extraKeys');
        $this->assertArrayHasKey('test', $extraKeys);

        $this->assertArrayNotHasKey('test', $this->request);
    }

    public function testExtraKeyToArray()
    {
        $this->initExtraKey();

        $array = $this->request->toArray();

        $this->assertArrayNotHasKey('test', $array, 'toArray不会带上额外键名');
    }

    public function testExtraKeyForEach()
    {
        $this->initExtraKey();

        $array = [];
        foreach ($this->request as $key => $value) {
            $array[$key] = $value;
        }

        $this->assertArrayNotHasKey('test', $array, 'forEach不会带上额外键名');
    }

    public function testExtraKeySet()
    {
        $this->initExtraKey();

        // 触发的是offsetSet,因此不会生成extraKey
        $this->request['test'] = 'value';

        $this->assertArrayNotHasKey('test', $this->request->getOption('extraKeys'));

        $this->assertEquals('value', $this->request->toArray()['test']);
    }

    public function testExtraKeySetMultiLevel()
    {
        $this->initExtraKey();

        // 触发的是offsetGet,因此会生成extraKey
        $this->request['test']['level2'] = 'value';

        $this->assertArrayHasKey('test', $this->request->getOption('extraKeys'));

        $this->assertEquals('value', $this->request['test']['level2']);

        $this->assertEquals('value', $this->request->toArray()['test']['level2']);
    }

    public function testExtraKeyCount()
    {
        if (isset($this->request['test'])) {
            unset($this->request['test']);
        }

        $count = count($this->request);

        $this->initExtraKey();

        $this->assertCount($count, $this->request);
    }

    public function testExtraKeyUnset()
    {
        $this->initExtraKey();

        $this->request['test']['level2'] = 'value';

        unset($this->request['test']);

        $this->assertArrayNotHasKey('test', $this->request->getOption('extraKeys'));
        $this->assertArrayNotHasKey('test', $this->request->toArray());
    }

    public function testExtraKeySetNull()
    {
        $this->initExtraKey();

        // 主动设置了,不会在extraKey里面
        $this->request['test'] = null;

        $this->assertArrayNotHasKey('test', $this->request->getOption('extraKeys'));

        $this->assertArrayHasKey('test', $this->request->toArray());
    }

    /**
     * @dataProvider providerForIsUrlRewrite
     * @param array $options
     * @param bool $result
     */
    public function testIsUrlRewrite(array $options, bool $result)
    {
        $request = new Request($options + ['wei' => $this->wei, 'fromGlobal' => false]);
        $this->assertSame($result, $request->isUrlRewrite());
    }

    public function providerForIsUrlRewrite()
    {
        return [
            [
                [
                    'pathInfo' => '/',
                    'defaultUrlRewrite' => false,
                ],
                false,
            ],
            [
                [
                    'pathInfo' => '/test',
                    'defaultUrlRewrite' => false,
                ],
                true,
            ],
            [
                [
                    'pathInfo' => '/',
                    'defaultUrlRewrite' => true,
                ],
                true,
            ],
            [
                [
                    'pathInfo' => '/test',
                    'defaultUrlRewrite' => true,
                ],
                true,
            ],
        ];
    }

    /**
     * @dataProvider providerForGetRouterPathInfo
     * @param array $options
     * @param string $pathInfo
     * @param string $routerPathInfo
     */
    public function testGetRouterPathInfo(array $options, string $pathInfo, string $routerPathInfo)
    {
        $request = new Request(['wei' => $this->wei, 'fromGlobal' => false] + $options);
        $this->assertSame($pathInfo, $request->getPathInfo());
        $this->assertSame($routerPathInfo, $request->getRouterPathInfo());
    }

    public function providerForGetRouterPathInfo()
    {
        return [
            [
                [
                    'pathInfo' => '/',
                    'defaultUrlRewrite' => false,
                ],
                '/',
                '/',
            ],
            [
                [
                    // We don't know whether the sever has enabled URL rewrite,
                    // default to not enabled
                    'pathInfo' => '/',
                    'defaultUrlRewrite' => false,
                    'data' => [
                        'r' => 'test',
                    ],
                ],
                '/',
                '/test',
            ],
            [
                [
                    // The server has enabled URL rewrite(has path info), ignore router param
                    'pathInfo' => '/test',
                    'defaultUrlRewrite' => false,
                    'data' => [
                        'r' => 'abc',
                    ],
                ],
                '/test',
                '/test',
            ],
            [
                [
                    'pathInfo' => '/',
                    'defaultUrlRewrite' => true,
                ],
                '/',
                '/',
            ],
            [
                [
                    'pathInfo' => '/',
                    // Ignore router param
                    'defaultUrlRewrite' => true,
                    'data' => [
                        'r' => 'abc',
                    ],
                ],
                '/',
                '/',
            ],
            [
                [
                    'pathInfo' => '/test',
                    'defaultUrlRewrite' => true,
                ],
                '/test',
                '/test',
            ],
            [
                [
                    'pathInfo' => '/test',
                    // Ignore router param
                    'defaultUrlRewrite' => true,
                    'data' => [
                        'r' => 'abc',
                    ],
                ],
                '/test',
                '/test',
            ],
        ];
    }

    public function testPopulateJsonToData()
    {
        // create request wei from custom parameter
        $request = new \Wei\Request([
            'wei' => $this->wei,
            'fromGlobal' => false,
            'content' => '{"a":"b"}',
            'servers' => [
                'HTTP_CONTENT_TYPE' => 'application/json',
            ],
        ]);
        $this->assertEquals('b', $request->get('a'));
    }

    protected function initExtraKey()
    {
        // 移除数据避免干扰
        unset($this->request['test']);
        $this->assertArrayNotHasKey('test', $this->request);

        $extraKeys = $this->request->getOption('extraKeys');
        $this->assertArrayNotHasKey('test', $extraKeys);

        // 触发了 &offsetGet 产生了 test 键名
        $this->request['test'];
    }
}
