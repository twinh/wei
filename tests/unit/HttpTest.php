<?php

namespace WeiTest;

use \Wei\Http;

/**
 * @method \Wei\Http http($options)
 * @property \Wei\Http $http
 */
class HttpTest extends TestCase
{
    /**
     * The basic URL for http wei
     *
     * @var string
     */
    protected $url;

    public $triggeredEvents;

    protected function setUp()
    {
        parent::setUp();

        $this->triggeredEvents = array();

        $this->url = $this->http->getUrl();

        if (false === @fopen($this->url, 'r')) {
            $this->markTestSkipped(sprintf('URL %s is not available', $this->url));
        }

        $this->wei->setConfig('http', array(
            'throwException' => false,
            'header' => true
        ));
    }

    /**
     * @dataProvider providerForSuccess
     */
    public function testSuccess($options)
    {
        $test = $this;
        $http = $this->http(array(
            'beforeSend' => function() use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertTrue(true);
            },
            'success' => function() use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertTrue(true);
            },
            'complete' => function() use($test) {
                $test->triggeredEvents[] = 'complete';
                $test->assertTrue(true);
            }
        ) + $options);

        $this->assertTrue($http->isSuccess());

        $this->assertHttpedEvents(array('beforeSend', 'success', 'complete'));
    }

    public function providerForSuccess()
    {
        $url = $this->http->getOption('url');

        return array(
            array(array(
                'url' => $url
            )),
            array(array(
                'url' => $url . '?abc=cdf',
            )),
            array(array(
                'url' => $url . '#abc'
            )),
            array(array(
                'url' => $url . '?abc#abc'
            ))
         );
    }

    public function testUrlAndOptionsSyntax()
    {
        $test = $this;
        $this->http($this->url, array(
            'beforeSend' => function() use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertTrue(true);
            },
            'success' => function() use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertTrue(true);
            },
            'complete' => function() use($test) {
                $test->triggeredEvents[] = 'complete';
                $test->assertTrue(true);
            }
        ));
    }

    /**
     * @dataProvider providerForError
     */
    public function testError($options, $responseText)
    {
        $test = $this;

        $http = $this->http(array(
            'beforeSend' => function() use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertTrue(true);
            },
            'error' => function() use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertTrue(true);
            },
            'complete' => function(Http $http) use($test, $responseText) {
                $test->triggeredEvents[] = 'complete';
                $test->assertEquals($responseText, $http->getResponseText());
            }
        ) + $options);

        $this->assertFalse($http->isSuccess());

        $this->assertInstanceOf('\ErrorException', $http->getErrorException());

        $this->assertHttpedEvents(array('beforeSend', 'error', 'complete'));
    }

    public function providerForError()
    {
        $url = $this->http->getOption('url');

        return array(
            // 404 but return content
            array(array(
                'url' => $url . '?code=404'
            ), 'default text'),
            array(array(
                'url' => $url . '?code=500'
            ), 'default text'),
            // Couldn't resolve host '404.php.net'
            array(array(
                'url' => 'http://404.php.net/',
                // set ip to null to enable dns lookup
                'ip' => null,
            ), null),
        );
    }

    public function testThrowException()
    {
        $this->setExpectedException('\ErrorException', 'Not Found');

        $this->http(array(
            'url' => $this->http->getOption('url') . '?code=404',
            'header' => true,
            'throwException' => true,
        ));
    }

    public function testHttpErrorWithoutStatusText()
    {
        $this->setExpectedException('\ErrorException', 'HTTP request error');

        $this->http(array(
            'url' => $this->http->getOption('url') . '?code=404',
            'header' => false,
            'throwException' => true,
        ));
    }

    public function testHeaders()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=headers',
            'dataType' => 'json',
            'headers' => array(
                'Key' => 'Value',
                'Key-Name' => 'Value',
                'Key_Name' => 'Value with space' // overwrite previous header
            ),
            'success' => function($data, Http $http) use($test) {
                // header set by php script
                $test->assertEquals('value', $http->getResponseHeader('customHeader'));

                $test->assertEquals('Value', $data['KEY']);
                $test->assertEquals('Value with space', $data['KEY_NAME']);
            }
        ));

        $this->assertTrue($http->isSuccess());
    }

    public function testGetResponseHeader()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=headers',
            'dataType' => 'json',
            'headers' => array(
                'Key' => 'Value',
                'Key-Name' => 'Value',
                'Key_Name' => 'Value with space' // overwrite previous header
            ),
            'success' => function($data, Http $http) use($test) {
                    $header = $http->getResponseHeader();

                    $test->assertContains('customHeader', $header);
                    $test->assertContains('value', $header);

                    $test->assertNull($http->getResponseHeader('no this key'));
                }
        ));

        $this->assertTrue($http->isSuccess());
    }

    public function testCustomOptions()
    {
        $test = $this;

        $this->http(array(
            'url' => $this->url,
            'customOption' => 'value',
            'beforeSend' => function(Http $http) use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertEquals('value', $http->customOption);
            }
        ));

        $this->assertHttpedEvents(array('beforeSend'));
    }

    public function testSetCustomOptions()
    {
        $test = $this;

        $http = $this->http(array(
            'url' => $this->url,
            'beforeSend' => function(Http $http) use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $http->customOption = 'value';
            },
            'success' => function($data, Http $http) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $http->customOption);
            }
        ));

        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('beforeSend', 'success'));
    }

    public function testQueryDataType()
    {
        $test = $this;
        $this->http(array(
            'url' => $this->url . '?type=query',
            'dataType' => 'query',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', $data['code']);
                $test->assertEquals('success', $data['message']);
            }
        ));
        $this->assertHttpedEvents(array('success'));
    }

    public function testJsonDataType()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'jsonObject',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data->code);
                $test->assertEquals('success', $data->message);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
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
        $test = $this;
        $data = array(
            'key' => 'value',
            'post' => true,
            'array' => array(
                1,
                'string' => 'value'
            )
        );
        $http = $this->http->postJson($this->url . '?test=post', $data);

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $test->assertEquals('POST', $data['method']);
        $test->assertEquals('value', $data['key']);
        $test->assertEquals('1', $data['post']);
        $test->assertEquals('1', $data['array'][0]);
        $test->assertEquals('value', $data['array']['string']);
    }

    public function testSerializeDataType()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?type=serialize',
            'dataType' => 'serialize',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data['code']);
                $test->assertEquals('success', $data['message']);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));

        // Parse error
        $test->triggeredEvents = array();
        $http = $this->http(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'serialize',
            'error' => function(Http $http, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertFalse($http->isSuccess());
        $this->assertHttpedEvents(array('error'));
    }

    public function testXmlDataType()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?type=xml',
            'dataType' => 'xml',
            'success' => function(\SimpleXMLElement $data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', (string)$data->code);
                $test->assertEquals('success', (string)$data->message);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));

        $this->triggeredEvents = array();
        $http = $this->http(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'xml',
            'error' => function($http, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertFalse($http->isSuccess());
        $this->assertHttpedEvents(array('error'));
    }

    public function testUserAgent()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => 'Test',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('Test', $data);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));

        $test->triggeredEvents = array();
        $http = $this->http(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('', $data);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testReferer()
    {
        $referer = 'http://google.com';
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=referer',
            'referer' => $referer,
            'success' => function($data) use($test, $referer) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals($referer, $data);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testAutoReferer()
    {
        $url = $this->url . '?test=referer';
        $test = $this;
        $http = $this->http(array(
            'url' => $url,
            'referer' => true, // Equals to current request URL
            'success' => function($data) use($test, $url) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals($url, $data);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testCookie()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=cookie',
            'dataType' => 'jsonObject',
            'cookies' => array(
                'key' => 'value',
                'bool' => true,
                'invalid' => ';"',
                'space' => 'S P'
            ),
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('1', $data->bool);
                $test->assertEquals(';"', $data->invalid);
                $test->assertEquals('S P', $data->space);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testGetCookie()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=responseCookies',
            'header' => true,
            'dataType' => 'json',
            'cookies' => array(
                'key' => 'value',
                'bool' => true,
                'invalid' => ';"',
                'space' => 'S P'
            ),
            'success' => function($data, Http $http) use($test) {
                $test->triggeredEvents[] = 'success';
                $cookies = $http->getResponseCookies();
                $test->assertInternalType('array', $cookies);

                $test->assertEquals('value', $cookies['key']);
                $test->assertEquals('1', $cookies['bool']);
                $test->assertEquals(';"', $cookies['invalid']);
                $test->assertEquals('S P', $cookies['space']);

                $test->assertEquals('value', $http->getResponseCookie('key'));
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testIgnoreDeletedCookie()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=responseCookies',
            'header' => true,
            'dataType' => 'json',
            'cookies' => array(
                'key' => 'value',
                'key1' => '',
                'key2' => false,
                'key3' => null,
                'key4' => 0,
                'key5' => 'deleted'
            ),
            'success' => function($data, Http $http) use($test) {
                $test->triggeredEvents[] = 'success';

                $cookies = $http->getResponseCookies();
                $test->assertInternalType('array', $cookies);

                $test->assertEquals('value', $cookies['key']);

                $test->assertArrayNotHasKey('key1', $cookies);
                $test->assertArrayNotHasKey('key2', $cookies);
                $test->assertArrayNotHasKey('key3', $cookies);

                $test->assertEquals('0', $cookies['key4']);
                $test->assertEquals('deleted', $cookies['key5']);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testPost()
    {
        $test = $this;
        $data = array(
            'key' => 'value',
            'post' => true,
            'array' => array(
                1,
                'string' => 'value'
            )
        );
        $http = $this->http->post($this->url . '?test=post', $data, 'jsonObject');

        $data = $http->getResponse();
        $this->assertTrue($http->isSuccess());
        $test->assertEquals('value', $data->key);
        $test->assertEquals('1', $data->post);
        $test->assertEquals('1', $data->array->{0});
        $test->assertEquals('value', $data->array->string);
    }

    /**
     * @dataProvider providerForMethods
     */
    public function testMethods($method)
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=methods',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test, $method) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(strtoupper($method), $data->method);
                $test->assertEquals('v', $data->data->k);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    /**
     * @dataProvider providerForGetMethods
     */
    public function testGet2($method)
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=get',
            'method' => $method,
            'dataType' => 'jsonObject',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('v', $data->k);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function providerForGetMethods()
    {
        // The result is depend on the server configuration
        return array(             // Apache               PHP 5.4 cli web server
            array('GET'),         // OK                   OK
            //array('HEAD'),      // No content           200 But no content
            //array('TRACE'),     // Method Not Allowed   OK
            array('OPTIONS'),     // OK                   OK
            //array('CONNECT'),   // Bad                  Request Invalid request (Malformed HTTP request)
            //array('CUSTOM')     // OK                   Request Invalid request (Malformed HTTP request)
        );
    }

    public function providerForMethods()
    {
        return array(
            array('DELETE'),
            array('PUT'),
            array('PATCH'),
            array('pAtCh'),
        );
    }

    /**
     * @dataProvider providerForAliasMethods
     */
    public function testAliasMethod($method)
    {
        /** @var $http Http */
        $http = $this->http->{strtolower($method)}($this->url . '?test=methods', array(), 'jsonObject');
        $this->assertEquals($method, $http->getResponse()->method);
        $this->assertTrue($http->isSuccess());
    }

    public function providerForAliasMethods()
    {
        return array(
            array('GET'),
            //array('POST'), Malformed HTTP request why?
            array('DELETE'),
            array('PUT'),
            array('PATCH')
        );
    }

    public function testTimeout()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?wait=0.1',
            'dataType' => 'jsonObject',
            'timeout' => 50,
            'error' => function(Http $http, $textStatus) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('curl', $textStatus);
            }
        ));
        $this->assertFalse($http->isSuccess());
        $this->assertHttpedEvents(array('error'));
    }

    public function testGlobal()
    {
        $test = $this;
        $this->wei->setConfig(array(
            'http' => array(
                'method' => 'post',
            ),
            'global.http' => array(
                'global' => true,
            ),
            'notGlobal.http' => array(
                'global' => false
            ),
        ));

        $http = $this->wei->globalHttp(array(
            'url' => $this->url . '?test=methods',
            'global' => true,
            'dataType' => 'jsonObject',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('POST', $data->method);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));

        $this->triggeredEvents = array();
        $http = $this->wei->notGlobalHttp(array(
            'url' => $this->url . '?test=methods',
            'dataType' => 'jsonObject',
            'global' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('GET', $data->method);
            },
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));

        // reset method
        $this->wei->setConfig('http', array(
            'method' => 'get',
        ));
    }

    public function testStringAsData()
    {
        $test = $this;
        $http = $this->http(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'jsonObject',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('10', $data->number);
            }
        ));
        $this->assertTrue($http->isSuccess());
        $this->assertHttpedEvents(array('success'));
    }

    public function testFlatApi()
    {
        // Success
        /** @var $http \Wei\Http */
        $http = $this->http(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'json'
        ));

        $this->assertTrue($http->isSuccess());

        $result = $http->getResponse();

        $this->assertInternalType('array', $result);

        // Error
        $http = $this->http(array(
            'url' => $this->url . '?code=404',
            'error' => function(){
                // overwrite the default error handler
            }
        ));
        $this->assertFalse($http->isSuccess());
        $this->assertEquals('http', $http->getErrorStatus());
        $this->assertInstanceOf('\ErrorException', $http->getErrorException());
    }

    public function testGetMethod()
    {
        $http = new \Wei\Http(array(
            'wei' => $this->wei,
            'method' => 'GET',
        ));
        $this->assertEquals('GET', $http->getMethod());

        $http = new \Wei\Http(array(
            'wei' => $this->wei,
            'method' => 'test',
        ));
        $this->assertEquals('TEST', $http->getMethod());
    }

    public function testGetIp()
    {
        $http = new \Wei\Http(array(
            'wei' => $this->wei,
            'ip' => '8.8.8.8'
        ));
        $this->assertEquals('8.8.8.8', $http->getIp());
    }

    public function testGetData()
    {
        $http = new \Wei\Http(array(
            'wei' => $this->wei,
            'data' => array(
                'key' => 'value'
            )
        ));
        $this->assertEquals(array('key' => 'value'), $http->getData());

        $http = new \Wei\Http(array(
            'wei' => $this->wei,
            'data' => 'string'
        ));
        $this->assertEquals('string', $http->getData());
    }

    public function testArrayAccess()
    {
        $data = $this->http(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ));
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
        $data = $this->http(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ));
        $this->assertTrue($data->isSuccess());
        $this->assertEquals(3, count($data));
    }

    public function testIteratorAggregate()
    {
        $data = $this->http(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
        ));
        $this->assertTrue($data->isSuccess());
        $response = $data->getResponse();

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $response[$key]);
        }
    }

    public function testToString()
    {
        $http = $this->http($this->url, array(
            'data' => 'type=text'
        ));
        $this->assertEquals('default text', (string)$http);
    }

    public function testGetCurlInfo()
    {
        $http = $this->http($this->url);
        $info = $http->getCurlInfo();
        $this->assertInternalType('array', $info);
    }

    public function testGetCurlOption()
    {
        $http = $this->http($this->url, array(
            'header' => true
        ));
        $this->assertEquals(true, $http->getCurlOption(CURLOPT_HEADER));
    }

    public function testParseJsonError()
    {
        $this->setExpectedException('ErrorException', 'JSON parsing error');

        $this->http(array(
            'url' => $this->url,
            'dataType' => 'json',
            'throwException' => true,
        ));
    }

    public function testSetCurlOption()
    {
        $this->http->setCurlOption(CURLOPT_HEADER, 1);
        $this->assertEquals(1, $this->http->getCurlOption(CURLOPT_HEADER));
    }

    public function assertHttpedEvents($events)
    {
        foreach ((array)$events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }
}
