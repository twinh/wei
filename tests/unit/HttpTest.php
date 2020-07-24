<?php

namespace WeiTest;

use Wei\Http;

/**
 * @method \Wei\Http http($options)
 * @property \Wei\Http $http
 *
 * @internal
 */
final class HttpTest extends TestCase
{
    public $triggeredEvents;
    /**
     * The basic URL for http wei
     *
     * @var string
     */
    protected $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->triggeredEvents = [];

        $this->url = $this->http->getUrl();

        if (false === @fopen($this->url, 'r')) {
            $this->markTestSkipped(sprintf('URL %s is not available', $this->url));
        }

        $this->wei->setConfig('http', [
            'throwException' => false,
            'header' => true,
        ]);
    }

    /**
     * @dataProvider providerForSuccess
     * @param mixed $options
     */
    public function testSuccess($options)
    {
        $test = $this;
        $http = $this->http([
                'beforeSend' => function () use ($test) {
                    $test->triggeredEvents[] = 'beforeSend';
                    $test->assertTrue(true);
                },
                'success' => function () use ($test) {
                    $test->triggeredEvents[] = 'success';
                    $test->assertTrue(true);
                },
                'complete' => function () use ($test) {
                    $test->triggeredEvents[] = 'complete';
                    $test->assertTrue(true);
                },
            ] + $options);

        $this->assertTrue($http->isSuccess());

        $this->assertTriggeredEvents(['beforeSend', 'success', 'complete']);
    }

    public function providerForSuccess()
    {
        $url = $this->http->getOption('url');

        return [
            [[
                'url' => $url,
            ]],
            [[
                'url' => $url . '?abc=cdf',
            ]],
            [[
                'url' => $url . '#abc',
            ]],
            [[
                'url' => $url . '?abc#abc',
            ]],
        ];
    }

    public function testUrlAndOptionsSyntax()
    {
        $test = $this;
        $this->http($this->url, [
            'beforeSend' => function () use ($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertTrue(true);
            },
            'success' => function () use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertTrue(true);
            },
            'complete' => function () use ($test) {
                $test->triggeredEvents[] = 'complete';
                $test->assertTrue(true);
            },
        ]);
    }

    /**
     * @dataProvider providerForError
     * @param mixed $options
     * @param mixed $responseText
     */
    public function testError($options, $responseText)
    {
        $test = $this;

        $http = $this->http([
                'beforeSend' => function () use ($test) {
                    $test->triggeredEvents[] = 'beforeSend';
                    $test->assertTrue(true);
                },
                'error' => function () use ($test) {
                    $test->triggeredEvents[] = 'error';
                    $test->assertTrue(true);
                },
                'complete' => function (Http $http) use ($test, $responseText) {
                    $test->triggeredEvents[] = 'complete';
                    $test->assertEquals($responseText, $http->getResponseText());
                },
            ] + $options);

        $this->assertFalse($http->isSuccess());

        $this->assertInstanceOf('\ErrorException', $http->getErrorException());

        $this->assertTriggeredEvents(['beforeSend', 'error', 'complete']);
    }

    public function providerForError()
    {
        $url = $this->http->getOption('url');

        return [
            // 404 but return content
            [[
                'url' => $url . '?code=404',
            ], 'default text'],
            [[
                'url' => $url . '?code=500',
            ], 'default text'],
            // Couldn't resolve host '404.php.net'
            [[
                'url' => 'http://404.php.net/',
                // set ip to null to enable dns lookup
                'ip' => null,
            ], null],
        ];
    }

    public function testThrowException()
    {
        try {
            $this->http([
                'url' => 'http://httpbin.org/status/404',
                'ip' => false,
                'header' => true,
                'throwException' => true,
            ]);
        } catch (\Exception $e) {
            $this->assertEquals(strtoupper('Not Found'), strtoupper($e->getMessage()));
        }
    }

    public function testHttpErrorWithoutStatusText()
    {
        $this->setExpectedException('\ErrorException', 'HTTP request error');

        $this->http([
            'url' => 'http://httpbin.org/status/404',
            'ip' => false,
            'header' => false,
            'throwException' => true,
        ]);
    }

    public function testHeaders()
    {
        $test = $this;
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'ip' => false,
            'dataType' => 'json',
            'headers' => [
                'Key' => 'Value',
                'Key-Name' => 'Value with space',
            ],
            'success' => function ($data, Http $http) use ($test) {
                $test->assertEquals('Value', $data['headers']['Key']);
                $test->assertEquals('Value with space', $data['headers']['Key-Name']);
            },
        ]);

        $this->assertTrue($http->isSuccess());
    }

    public function testGetResponseHeader()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=headers',
            'dataType' => 'json',
            'headers' => [
                'Key' => 'Value',
                'Key-Name' => 'Value',
                'Key_Name' => 'Value with space', // overwrite previous header
            ],
            'success' => function ($data, Http $http) use ($test) {
                $header = $http->getResponseHeader();

                $test->assertStringContainsString('customHeader', $header);
                $test->assertStringContainsString('value', $header);

                $test->assertNull($http->getResponseHeader('no this key'));
            },
        ]);

        $this->assertTrue($http->isSuccess());
    }

    public function testCustomOptions()
    {
        $test = $this;

        $this->http([
            'url' => $this->url,
            'customOption' => 'value',
            'beforeSend' => function (Http $http) use ($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertEquals('value', $http->customOption);
            },
        ]);

        $this->assertTriggeredEvents(['beforeSend']);
    }

    public function testSetCustomOptions()
    {
        $test = $this;

        $http = $this->http([
            'url' => $this->url,
            'beforeSend' => function (Http $http) use ($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $http->customOption = 'value';
            },
            'success' => function ($data, Http $http) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $http->customOption);
            },
        ]);

        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['beforeSend', 'success']);
    }

    public function testQueryDataType()
    {
        $test = $this;
        $this->http([
            'url' => $this->url . '?type=query',
            'dataType' => 'query',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', $data['code']);
                $test->assertEquals('success', $data['message']);
            },
        ]);
        $this->assertTriggeredEvents(['success']);
    }

    public function testJsonDataType()
    {
        $test = $this;
        $http = $this->http([
            'url' => 'http://httpbin.org/ip',
            'ip' => false,
            'dataType' => 'jsonObject',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertObjectHasAttribute('origin', $data);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testGetJsonObject()
    {
        $http = $this->http->getJsonObject($this->url . '?type=json');
        $this->assertEquals(0, $http->getResponse()->code);
        $this->assertEquals('success', $http->getResponse()->message);

        $this->assertTrue($http->isSuccess());
    }

    public function testGetJson()
    {
        $http = $this->http->getJson($this->url . '?type=json');
        $data = $http->getResponse();

        $this->assertTrue($http->isSuccess());
        $this->assertEquals(0, $data['code']);
        $this->assertEquals('success', $data['message']);
        $this->assertEquals('GET', $data['method']);
    }

    public function testPostJson()
    {
        $data = [
            'key' => 'value',
            'post' => true,
            'file' => '@dff', // not file
            'array' => [
                1,
                'string' => 'value',
            ],
        ];
        $http = $this->http->postJson($this->url . '?test=post', $data);

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $this->assertEquals('POST', $data['method']);
        $this->assertEquals('value', $data['key']);
        $this->assertEquals('1', $data['post']);
        $this->assertEquals('@dff', $data['file']);
        $this->assertEquals('1', $data['array'][0]);
        $this->assertEquals('value', $data['array']['string']);
    }

    public function testUpload()
    {
        $http = $this->http([
            'method' => 'post',
            'dataType' => 'json',
            'url' => $this->url . '?test=post',
            'files' => [
                'php' => __FILE__,
            ],
        ]);

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $this->assertEquals('POST', $data['method']);
        $this->assertEquals('HttpTest.php', $data['files']['php']['name']);
    }

    public function testUploadWithPostData()
    {
        $data = [
            'key' => 'value',
            'post' => true,
            //'file' => '@dff', // not support with upload
            'array' => [
                1,
                'string' => 'value',
            ],
        ];
        $http = $this->http([
            'method' => 'post',
            'dataType' => 'json',
            'url' => $this->url . '?test=post',
            'data' => $data,
            'files' => [
                'php' => __FILE__,
            ],
        ]);

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $this->assertEquals('POST', $data['method']);
        $this->assertEquals('value', $data['key']);
        $this->assertEquals('1', $data['post']);
        $this->assertEquals('1', $data['array'][0]);
        $this->assertEquals('value', $data['array']['string']);
        $this->assertEquals('HttpTest.php', $data['files']['php']['name']);
    }

    public function testSerializeDataType()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?type=serialize',
            'dataType' => 'serialize',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data['code']);
                $test->assertEquals('success', $data['message']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        // Parse error
        $test->triggeredEvents = [];
        $http = $this->http([
            'url' => $this->url . '?type=json',
            'dataType' => 'serialize',
            'error' => function (Http $http, $textStatus, $exception) use ($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testXmlDataType()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?type=xml',
            'dataType' => 'xml',
            'success' => function (\SimpleXMLElement $data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', (string) $data->code);
                $test->assertEquals('success', (string) $data->message);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        $this->triggeredEvents = [];
        $http = $this->http([
            'url' => $this->url . '?type=json',
            'dataType' => 'xml',
            'error' => function ($http, $textStatus, $exception) use ($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testUserAgent()
    {
        $test = $this;
        $http = $this->wei->newInstance('http')->__invoke([
            'url' => 'http://httpbin.org/user-agent',
            'ip' => false,
            'userAgent' => 'Test',
            'dataType' => 'json',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('Test', $data['user-agent']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        $test->triggeredEvents = [];
        $http = $this->http([
            'url' => 'http://httpbin.org/user-agent',
            'ip' => false,
            'userAgent' => false,
            'dataType' => 'json',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('', $data['user-agent']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testReferer()
    {
        $referer = 'http://google.com';
        $test = $this;
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'ip' => false,
            'referer' => $referer,
            'dataType' => 'json',
            'success' => function ($data) use ($test, $referer) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals($referer, $data['headers']['Referer']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testAutoReferer()
    {
        $test = $this;
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'ip' => false,
            'referer' => true, // Equals to current request URL
            'dataType' => 'json',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('http://httpbin.org/headers', $data['headers']['Referer']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testCookie()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=cookie',
            'dataType' => 'jsonObject',
            'cookies' => [
                'key' => 'value',
                'bool' => true,
                'invalid' => ';"',
                'space' => 'S P',
            ],
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('1', $data->bool);
                $test->assertEquals(';"', $data->invalid);
                $test->assertEquals('S P', $data->space);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testGetCookie()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=responseCookies',
            'header' => true,
            'dataType' => 'json',
            'cookies' => [
                'key' => 'value',
                'bool' => true,
                'invalid' => ';"',
                'space' => 'S P',
            ],
            'success' => function ($data, Http $http) use ($test) {
                $test->triggeredEvents[] = 'success';
                $cookies = $http->getResponseCookies();
                $test->assertIsArray($cookies);

                $test->assertEquals('value', $cookies['key']);
                $test->assertEquals('1', $cookies['bool']);
                $test->assertEquals(';"', $cookies['invalid']);
                $test->assertEquals('S P', $cookies['space']);

                $test->assertEquals('value', $http->getResponseCookie('key'));
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testIgnoreDeletedCookie()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=responseCookies',
            'header' => true,
            'dataType' => 'json',
            'cookies' => [
                'key' => 'value',
                'key1' => '',
                'key2' => false,
                'key3' => null,
                'key4' => 0,
                'key5' => 'deleted',
            ],
            'success' => function ($data, Http $http) use ($test) {
                $test->triggeredEvents[] = 'success';

                $cookies = $http->getResponseCookies();
                $test->assertIsArray($cookies);

                $test->assertEquals('value', $cookies['key']);

                $test->assertArrayNotHasKey('key1', $cookies);
                $test->assertArrayNotHasKey('key2', $cookies);
                $test->assertArrayNotHasKey('key3', $cookies);

                $test->assertEquals('0', $cookies['key4']);
                $test->assertEquals('deleted', $cookies['key5']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testPost()
    {
        $test = $this;
        $data = [
            'key' => 'value',
            'post' => true,
        ];
        /** @var $http \Wei\Http */
        $http = $this->wei->newInstance('http', ['ip' => false])->post('http://httpbin.org/post', $data, 'jsonObject');

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $test->assertEquals('value', $data->form->key);
        $test->assertEquals('1', $data->form->post);
    }

    /**
     * @dataProvider providerForMethods
     * @param mixed $method
     */
    public function testMethods($method)
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=methods',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => [
                'k' => 'v',
            ],
            'success' => function ($data) use ($test, $method) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(strtoupper($method), $data->method);
                $test->assertEquals('v', $data->data->k);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    /**
     * @dataProvider providerForGetMethods
     * @param mixed $method
     */
    public function testGet2($method)
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=get',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => [
                'k' => 'v',
            ],
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('v', $data->k);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function providerForGetMethods()
    {
        // The result is depend on the server configuration
        return [             // Apache               PHP 5.4 cli web server
            ['GET'],         // OK                   OK
            //array('HEAD'),      // No content           200 But no content
            //array('TRACE'),     // Method Not Allowed   OK
            ['OPTIONS'],     // OK                   OK
            //array('CONNECT'),   // Bad                  Request Invalid request (Malformed HTTP request)
            //array('CUSTOM')     // OK                   Request Invalid request (Malformed HTTP request)
        ];
    }

    public function providerForMethods()
    {
        return [
            ['DELETE'],
            ['PUT'],
            ['PATCH'],
            ['pAtCh'],
        ];
    }

    /**
     * @dataProvider providerForAliasMethods
     * @param mixed $method
     */
    public function testAliasMethod($method)
    {
        /** @var $http Http */
        $http = $this->http->{strtolower($method)}($this->url . '?test=methods', [], 'jsonObject');
        $this->assertEquals($method, $http->getResponse()->method);
        $this->assertTrue($http->isSuccess());
    }

    public function providerForAliasMethods()
    {
        return [
            ['GET'],
            //array('POST'), Malformed HTTP request why?
            ['DELETE'],
            ['PUT'],
            ['PATCH'],
        ];
    }

    public function testTimeout()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?wait=0.1',
            'dataType' => 'jsonObject',
            'timeout' => 50,
            'error' => function (Http $http, $textStatus) use ($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('curl', $textStatus);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testGlobal()
    {
        $test = $this;
        $this->wei->setConfig([
            'http' => [
                'method' => 'post',
            ],
            'global.http' => [
                'global' => true,
            ],
            'notGlobal.http' => [
                'global' => false,
            ],
        ]);

        $http = $this->wei->globalHttp([
            'url' => $this->url . '?test=methods',
            'global' => true,
            'dataType' => 'jsonObject',
            'data' => [
                'k' => 'v',
            ],
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('POST', $data->method);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        $this->triggeredEvents = [];
        $http = $this->wei->notGlobalHttp([
            'url' => $this->url . '?test=methods',
            'dataType' => 'jsonObject',
            'global' => false,
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('GET', $data->method);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        // reset method
        $this->wei->setConfig('http', [
            'method' => 'get',
        ]);
    }

    public function testStringAsData()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'jsonObject',
            'success' => function ($data) use ($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('10', $data->number);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testFlatApi()
    {
        // Success
        /** @var $http \Wei\Http */
        $http = $this->http([
            'url' => $this->url . '?type=json',
            'dataType' => 'json',
        ]);

        $this->assertTrue($http->isSuccess());

        $result = $http->getResponse();

        $this->assertIsArray($result);

        // Error
        $http = $this->http([
            'url' => $this->url . '?code=404',
            'error' => function () {
                // overwrite the default error handler
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertEquals('http', $http->getErrorStatus());
        $this->assertInstanceOf('\ErrorException', $http->getErrorException());
    }

    public function testGetMethod()
    {
        $http = new \Wei\Http([
            'wei' => $this->wei,
            'method' => 'GET',
        ]);
        $this->assertEquals('GET', $http->getMethod());

        $http = new \Wei\Http([
            'wei' => $this->wei,
            'method' => 'test',
        ]);
        $this->assertEquals('TEST', $http->getMethod());
    }

    public function testGetIp()
    {
        $http = new \Wei\Http([
            'wei' => $this->wei,
            'ip' => '8.8.8.8',
        ]);
        $this->assertEquals('8.8.8.8', $http->getIp());
    }

    public function testGetData()
    {
        $http = new \Wei\Http([
            'wei' => $this->wei,
            'data' => [
                'key' => 'value',
            ],
        ]);
        $this->assertEquals(['key' => 'value'], $http->getData());

        $http = new \Wei\Http([
            'wei' => $this->wei,
            'data' => 'string',
        ]);
        $this->assertEquals('string', $http->getData());
    }

    public function testArrayAccess()
    {
        $data = $this->http([
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ]);
        $this->assertTrue($data->isSuccess());

        $this->assertTrue(isset($data['key']));
        $this->assertFalse(isset($data['notExits']));

        $this->assertEquals('value', $data['key']);
        $this->assertEquals('10', $data['number']);

        unset($data['key']);
        $this->assertNull($data['key']);

        $data['key'] = 'v2';
        $this->assertEquals('v2', $data['key']);
    }

    public function testCountable()
    {
        $data = $this->http([
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ]);
        $this->assertTrue($data->isSuccess());
        $this->assertEquals(3, count($data));
    }

    public function testIteratorAggregate()
    {
        $data = $this->http([
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ]);
        $this->assertTrue($data->isSuccess());
        $response = $data->getResponse();

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $response[$key]);
        }
    }

    public function testToString()
    {
        $http = $this->http($this->url, [
            'data' => 'type=text',
        ]);
        $this->assertEquals('default text', (string) $http);
    }

    public function testGetCurlInfo()
    {
        $http = $this->http($this->url);
        $info = $http->getCurlInfo();
        $this->assertIsArray($info);
    }

    public function testGetCurlInfoWithOption()
    {
        $http = $this->http($this->url);
        $this->assertEquals(200, $http->getCurlInfo(CURLINFO_HTTP_CODE));
    }

    public function testGetCurlOption()
    {
        $http = $this->http($this->url, [
            'header' => true,
        ]);
        $this->assertEquals(true, $http->getCurlOption(CURLOPT_HEADER));
    }

    public function testParseJsonError()
    {
        $this->setExpectedException('ErrorException', 'JSON parsing error');

        $this->http([
            'url' => $this->url,
            'dataType' => 'json',
            'throwException' => true,
        ]);
    }

    public function testSetCurlOption()
    {
        $this->http->setCurlOption(CURLOPT_HEADER, 1);
        $this->assertEquals(1, $this->http->getCurlOption(CURLOPT_HEADER));
    }

    public function assertTriggeredEvents($events)
    {
        foreach ((array) $events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }

    public function testRetryTwoTimesAndFail()
    {
        $test = $this;
        $http = $this->http([
            'url' => $this->url . '?code=404',
            'retries' => 1,
            'throwException' => false,
            'beforeSend' => function () use ($test) {
                $test->triggeredEvents[] = 'beforeSend';
            },
            'error' => function () use ($test) {
                $test->triggeredEvents[] = 'error';
            },
            'complete' => function (Http $http) use ($test) {
                $test->triggeredEvents[] = 'complete';
            },
        ]);

        $this->assertFalse($http->isSuccess());

        $this->assertTriggeredEvents([
            'beforeSend', 'error', 'complete',
            'beforeSend', 'error', 'complete', // Retry
        ]);

        $this->assertEquals(0, $http->getOption('leftRetries'));
    }

    public function testRetryAndSuccess()
    {
        $http = $this->createPartialMock('\Wei\Http', ['handleResponse']);

        $http->expects($this->at(0))
            ->method('handleResponse')
            ->willReturnCallback(function () use ($http) {
                $http->setOption('result', false);
            });

        $http->expects($this->at(1))
            ->method('handleResponse')
            ->willReturnCallback(function () use ($http) {
                $http->setOption('result', true);
            });

        $http->setOption([
            'retries' => 2,
        ]);
        $http->execute();

        $this->assertEquals(1, $http->getOption('leftRetries'));
    }

    public function testGetValueWhenResponseIsNotArray()
    {
        $http = $this->http;

        $this->assertFalse(isset($http['test']));

        $this->assertNull($http['test']);
    }
}
