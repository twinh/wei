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
    protected $triggeredEvents = [];

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
        $http = $this->http([
                'beforeSend' => function () {
                    $this->triggeredEvents[] = 'beforeSend';
                    $this->assertTrue(true);
                },
                'success' => function () {
                    $this->triggeredEvents[] = 'success';
                    $this->assertTrue(true);
                },
                'complete' => function () {
                    $this->triggeredEvents[] = 'complete';
                    $this->assertTrue(true);
                },
            ] + $options);

        $this->assertTrue($http->isSuccess());

        $this->assertTriggeredEvents(['beforeSend', 'success', 'complete']);
    }

    public function providerForSuccess()
    {
        $url = $this->http->getOption('url');

        return [
            [
                [
                    'url' => $url,
                ],
            ],
            [
                [
                    'url' => $url . '?abc=cdf',
                ],
            ],
            [
                [
                    'url' => $url . '#abc',
                ],
            ],
            [
                [
                    'url' => $url . '?abc#abc',
                ],
            ],
        ];
    }

    public function testUrlAndOptionsSyntax()
    {
        $this->http($this->url, [
            'beforeSend' => function () {
                $this->triggeredEvents[] = 'beforeSend';
                $this->assertTrue(true);
            },
            'success' => function () {
                $this->triggeredEvents[] = 'success';
                $this->assertTrue(true);
            },
            'complete' => function () {
                $this->triggeredEvents[] = 'complete';
                $this->assertTrue(true);
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
        $http = $this->http([
                'beforeSend' => function () {
                    $this->triggeredEvents[] = 'beforeSend';
                    $this->assertTrue(true);
                },
                'error' => function () {
                    $this->triggeredEvents[] = 'error';
                    $this->assertTrue(true);
                },
                'complete' => function (Http $http) use ($responseText) {
                    $this->triggeredEvents[] = 'complete';
                    $this->assertEquals($responseText, $http->getResponseText());
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
            [
                [
                    'url' => $url . '?code=404',
                ],
                'default text',
            ],
            [
                [
                    'url' => $url . '?code=500',
                ],
                'default text',
            ],
            // Couldn't resolve host '404.php.net'
            [
                [
                    'url' => 'http://404.php.net/',
                ],
                null,
            ],
        ];
    }

    public function testThrowException()
    {
        try {
            $this->http([
                'url' => 'http://httpbin.org/status/404',
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
            'header' => false,
            'throwException' => true,
        ]);
    }

    public function testHeaders()
    {
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'dataType' => 'json',
            'headers' => [
                'Key' => 'Value',
                'Key-Name' => 'Value with space',
            ],
            'success' => function ($data, Http $http) {
                $this->assertEquals('Value', $data['headers']['Key']);
                $this->assertEquals('Value with space', $data['headers']['Key-Name']);
            },
        ]);

        $this->assertTrue($http->isSuccess());
    }

    public function testGetResponseHeader()
    {
        $http = $this->http([
            'url' => $this->url . '?test=headers',
            'dataType' => 'json',
            'headers' => [
                'Key' => 'Value',
                'Key-Name' => 'Value',
                'Key_Name' => 'Value with space', // overwrite previous header
            ],
            'success' => function ($data, Http $http) {
                $header = $http->getResponseHeader();

                $this->assertStringContainsString('customHeader', $header);
                $this->assertStringContainsString('value', $header);

                $this->assertNull($http->getResponseHeader('no this key'));
            },
        ]);

        $this->assertTrue($http->isSuccess());
    }

    public function testCustomOptions()
    {
        $this->http([
            'url' => $this->url,
            'customOption' => 'value',
            'beforeSend' => function (Http $http) {
                $this->triggeredEvents[] = 'beforeSend';
                $this->assertEquals('value', $http->customOption);
            },
        ]);

        $this->assertTriggeredEvents(['beforeSend']);
    }

    public function testSetCustomOptions()
    {
        $http = $this->http([
            'url' => $this->url,
            'beforeSend' => function (Http $http) {
                $this->triggeredEvents[] = 'beforeSend';
                $http->customOption = 'value';
            },
            'success' => function ($data, Http $http) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('value', $http->customOption);
            },
        ]);

        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['beforeSend', 'success']);
    }

    public function testQueryDataType()
    {
        $this->http([
            'url' => $this->url . '?type=query',
            'dataType' => 'query',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('0', $data['code']);
                $this->assertEquals('success', $data['message']);
            },
        ]);
        $this->assertTriggeredEvents(['success']);
    }

    public function testJsonDataType()
    {
        $http = $this->http([
            'url' => 'http://httpbin.org/ip',
            'dataType' => 'jsonObject',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertObjectHasAttribute('origin', $data);
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
        $http = $this->http([
            'url' => $this->url . '?type=serialize',
            'dataType' => 'serialize',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals(0, $data['code']);
                $this->assertEquals('success', $data['message']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        // Parse error
        $this->triggeredEvents = [];
        $http = $this->http([
            'url' => $this->url . '?type=json',
            'dataType' => 'serialize',
            'error' => function (Http $http, $textStatus, $exception) {
                $this->triggeredEvents[] = 'error';
                $this->assertEquals('parser', $textStatus);
                $this->assertInstanceOf('\ErrorException', $exception);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testXmlDataType()
    {
        $http = $this->http([
            'url' => $this->url . '?type=xml',
            'dataType' => 'xml',
            'success' => function (\SimpleXMLElement $data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('0', (string) $data->code);
                $this->assertEquals('success', (string) $data->message);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        $this->triggeredEvents = [];
        $http = $this->http([
            'url' => $this->url . '?type=json',
            'dataType' => 'xml',
            'error' => function ($http, $textStatus, $exception) {
                $this->triggeredEvents[] = 'error';
                $this->assertEquals('parser', $textStatus);
                $this->assertInstanceOf('\ErrorException', $exception);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testUserAgent()
    {
        $http = $this->wei->newInstance('http')->__invoke([
            'url' => 'http://httpbin.org/user-agent',
            'userAgent' => 'Test',
            'dataType' => 'json',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('Test', $data['user-agent']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);

        $this->triggeredEvents = [];
        $http = $this->http([
            'url' => 'http://httpbin.org/user-agent',
            'userAgent' => false,
            'dataType' => 'json',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('', $data['user-agent']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testReferer()
    {
        $referer = 'http://google.com';
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'referer' => $referer,
            'dataType' => 'json',
            'success' => function ($data) use ($referer) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals($referer, $data['headers']['Referer']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testAutoReferer()
    {
        $http = $this->http([
            'url' => 'http://httpbin.org/headers',
            'referer' => true, // Equals to current request URL
            'dataType' => 'json',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('http://httpbin.org/headers', $data['headers']['Referer']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testCookie()
    {
        $http = $this->http([
            'url' => $this->url . '?test=cookie',
            'dataType' => 'jsonObject',
            'cookies' => [
                'key' => 'value',
                'bool' => true,
                'invalid' => ';"',
                'space' => 'S P',
            ],
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('value', $data->key);
                $this->assertEquals('1', $data->bool);
                $this->assertEquals(';"', $data->invalid);
                $this->assertEquals('S P', $data->space);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testGetCookie()
    {
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
            'success' => function ($data, Http $http) {
                $this->triggeredEvents[] = 'success';
                $cookies = $http->getResponseCookies();
                $this->assertIsArray($cookies);

                $this->assertEquals('value', $cookies['key']);
                $this->assertEquals('1', $cookies['bool']);
                $this->assertEquals(';"', $cookies['invalid']);
                $this->assertEquals('S P', $cookies['space']);

                $this->assertEquals('value', $http->getResponseCookie('key'));
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testIgnoreDeletedCookie()
    {
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
            'success' => function ($data, Http $http) {
                $this->triggeredEvents[] = 'success';

                $cookies = $http->getResponseCookies();
                $this->assertIsArray($cookies);

                $this->assertEquals('value', $cookies['key']);

                $this->assertArrayNotHasKey('key1', $cookies);
                $this->assertArrayNotHasKey('key2', $cookies);
                $this->assertArrayNotHasKey('key3', $cookies);

                $this->assertEquals('0', $cookies['key4']);
                $this->assertEquals('deleted', $cookies['key5']);
            },
        ]);
        $this->assertTrue($http->isSuccess());
        $this->assertTriggeredEvents(['success']);
    }

    public function testPost()
    {
        $http = $this->http->post([
            'url' => 'https://httpbin.org/post',
            'dataType' => 'jsonObject',
            'data' => [
                'key' => 'value',
                'post' => true,
            ],
        ]);

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());

        $this->assertEquals('value', $data->form->key);
        $this->assertEquals('1', $data->form->post);
    }

    /**
     * @dataProvider providerForMethods
     * @param mixed $method
     */
    public function testMethods($method)
    {
        $http = $this->http([
            'url' => $this->url . '?test=methods',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => [
                'k' => 'v',
            ],
            'success' => function ($data) use ($method) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals(strtoupper($method), $data->method);
                $this->assertEquals('v', $data->data->k);
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
        $http = $this->http([
            'url' => $this->url . '?test=get',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => [
                'k' => 'v',
            ],
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('v', $data->k);
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
        $http = $this->http->{strtolower($method)}('https://httpbin.org/' . strtolower($method), [
            'dataType' => 'jsonObject',
            'data' => [
                'method' => $method,
            ],
        ]);
        $response = $http->getResponse();
        $this->assertSame($method, $response->args->method ?? $response->form->method);
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
        $http = $this->http([
            'url' => $this->url . '?wait=0.1',
            'dataType' => 'jsonObject',
            'timeout' => 50,
            'error' => function (Http $http, $textStatus) {
                $this->triggeredEvents[] = 'error';
                $this->assertEquals('curl', $textStatus);
            },
        ]);
        $this->assertFalse($http->isSuccess());
        $this->assertTriggeredEvents(['error']);
    }

    public function testStringAsData()
    {
        $http = $this->http([
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'jsonObject',
            'success' => function ($data) {
                $this->triggeredEvents[] = 'success';
                $this->assertEquals('value', $data->key);
                $this->assertEquals('10', $data->number);
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

    public function testIp()
    {
        $http = $this->http->get([
            'url' => $this->url . '?type=json',
            'ip' => '127.0.0.1',
        ]);
        $this->assertTrue($http->isSuccess());

        // URL replaced to ip
        $this->assertStringContainsString('127.0.0.1', $http->getCurlOption(\CURLOPT_URL));
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
        $this->assertEquals(200, $http->getCurlInfo(\CURLINFO_HTTP_CODE));
    }

    public function testGetCurlOption()
    {
        $http = $this->http($this->url, [
            'header' => true,
        ]);
        $this->assertTrue($http->getCurlOption(\CURLOPT_HEADER));
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
        $http = $this->http;
        $http->setCurlOption(\CURLOPT_HEADER, 1);
        $this->assertEquals(1, $http->getCurlOption(\CURLOPT_HEADER));
    }

    public function assertTriggeredEvents($events)
    {
        foreach ((array) $events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }

    public function testRetryTwoTimesAndFail()
    {
        $http = $this->http([
            'url' => $this->url . '?code=404',
            'retries' => 1,
            'throwException' => false,
            'beforeSend' => function () {
                $this->triggeredEvents[] = 'beforeSend';
            },
            'error' => function () {
                $this->triggeredEvents[] = 'error';
            },
            'complete' => function (Http $http) {
                $this->triggeredEvents[] = 'complete';
            },
        ]);

        $this->assertFalse($http->isSuccess());

        $this->assertTriggeredEvents([
            'beforeSend',
            'error',
            'complete',
            'beforeSend',
            'error',
            'complete', // Retry
        ]);

        $this->assertEquals(0, $http->getOption('leftRetries'));
    }

    public function testRetryAndSuccess()
    {
        $http = $this->createPartialMock('\Wei\Http', ['handleResponse']);

        // @phpstan-ignore-next-line Trying to mock an undefined method handleResponse() on class \Wei\Http.
        $http->expects($this->at(0))
            ->method('handleResponse')
            ->willReturnCallback(function () use ($http) {
                $http->setOption('result', false);
            });

        // @phpstan-ignore-next-line Trying to mock an undefined method handleResponse() on class \Wei\Http.
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

    public function testToRet()
    {
        $http = $this->http->request([
            'url' => 'https://httpbin.org/get?a=b',
            'dataType' => 'json',
        ]);
        $ret = $http->toRet(['c' => 'd']);

        $this->assertRetSuc($ret);

        $this->assertSame('b', $ret['args']['a']);
        $this->assertSame('d', $ret['c']);
    }

    public function testToRetError()
    {
        $http = $this->http->request([
            'url' => 'https://httpbin.org/status/404',
            'header' => true,
            'throwException' => false,
        ]);
        $ret = $http->toRet(['c' => 'd']);

        $this->assertRetErr($ret);
        $this->assertSame('d', $ret['c']);
    }

    public function testRequestRet()
    {
        $ret = $this->http->requestRet([
            'url' => 'https://httpbin.org/get?a=b',
            'dataType' => 'json',
        ]);

        $this->assertRetSuc($ret);
        $this->assertSame('b', $ret['args']['a']);
    }

    /**
     * @dataProvider providerForParams
     * @param string $url
     * @param array $params
     * @param string $result
     */
    public function testParams(string $url, array $params, string $result)
    {
        $http = $this->http->get([
            'url' => $url,
            'dataType' => 'json',
            'params' => $params,
        ]);
        $response = $http->getResponse();
        $this->assertSame($result, $response['url']);
    }

    public function providerForParams(): array
    {
        return [
            [
                'https://httpbin.org/get',
                ['a' => 'b'],
                'https://httpbin.org/get?a=b',
            ],
            [
                'https://httpbin.org/get?a=b',
                ['c' => 'd'],
                'https://httpbin.org/get?a=b&c=d',
            ],
        ];
    }

    public function testDetectJson()
    {
        $http = $this->http->get([
            'url' => 'https://httpbin.org/get',
        ]);
        $this->assertSame('application/json', $http->getResponseHeader('CONTENT-TYPE'));

        $response = $http->getResponse();
        $this->assertIsArray($response);
        $this->assertSame('https://httpbin.org/get', $response['url']);
    }

    public function testNewInstance()
    {
        $this->assertNotSame(Http::instance(), Http::instance());
    }

    public function testJson()
    {
        $http = Http::post([
            'url' => 'https://httpbin.org/post',
            'json' => [
                'a' => 'b',
            ],
        ]);
        $this->assertSame('application/json', $http['headers']['Content-Type']);
        $this->assertSame('b', $http['json']['a']);
    }

    public function testUrl()
    {
        $http = Http::url('https://httpbin.org/post')
            ->params(['a' => 'b'])
            ->json(['e' => 'f'])
            ->method('POST')
            ->request();

        $this->assertSame('b', $http['args']['a']);
        $this->assertSame('f', $http['json']['e']);
    }
}
