<?php

namespace WidgetTest;

use Widget\Call;

/**
 * @method Call call($options)
 */
class CallTest extends TestCase
{
    /**
     * The basic URL for call widget
     *
     * @var string
     */
    protected $url;

    public $triggeredEvents;

    protected function setUp()
    {
        parent::setUp();

        $this->triggeredEvents = array();

        $this->url = $this->call->getUrl();

        if (false === @fopen($this->url, 'r')) {
            $this->markTestSkipped(sprintf('URL %s is not available', $this->url));
        }

        $this->call->setCurlOption(CURLOPT_HEADER, true);
        $this->call->setOption('throwException', false);

        $this->call->error(function($call, $type, $e){
            throw $e;
        });
    }

    /**
     * @dataProvider providerForSuccess
     */
    public function testSuccess($options)
    {
        $test = $this;
        $call = $this->call(array(
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

        $this->assertTrue($call->isSuccess());

        $this->assertCalledEvents(array('beforeSend', 'success', 'complete'));
    }

    public function providerForSuccess()
    {
        $url = $this->call->getOption('url');

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
        $this->call($this->url, array(
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

        $call = $this->call(array(
            'beforeSend' => function() use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertTrue(true);
            },
            'error' => function() use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertTrue(true);
            },
            'complete' => function($call) use($test, $responseText) {
                $test->triggeredEvents[] = 'complete';
                $test->assertEquals($responseText, $call->getResponseText());
            }
        ) + $options);

        $this->assertFalse($call->isSuccess());

        $this->assertInstanceOf('\ErrorException', $call->getErrorException());

        $this->assertCalledEvents(array('beforeSend', 'error', 'complete'));
    }

    public function providerForError()
    {
        $url = $this->call->getOption('url');

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

    public function testHeaders()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?test=headers',
            'dataType' => 'json',
            'headers' => array(
                'Key' => 'Value',
                'Key-Name' => 'Value',
                'Key_Name' => 'Value with space' // overwrite previous header
            ),
            'success' => function($data, $call) use($test) {
                // header set by php script
                $test->assertEquals('value', $call->getResponseHeader('customHeader'));

                $test->assertEquals('Value', $data['KEY']);
                $test->assertEquals('Value with space', $data['KEY_NAME']);
            }
        ));

        $this->assertTrue($call->isSuccess());
    }

    public function testLateBindingCallbacks()
    {
        $test = $this;

        $call = $this->call(array(
            'url' => $this->url,
            'beforeSend' => function(Call $call) use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $call->success(function() use($test) {
                    $test->triggeredEvents[] = 'success';
                })->complete(function() use($test) {
                    $test->triggeredEvents[] = 'complete';
                });
            }
        ));

        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('beforeSend', 'success', 'complete'));
    }

    public function testCustomOptions()
    {
        $test = $this;

        $this->call(array(
            'url' => $this->url,
            'customOption' => 'value',
            'beforeSend' => function(Call $call) use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $test->assertEquals('value', $call->customOption);
            }
        ));

        $this->assertCalledEvents(array('beforeSend'));
    }

    public function testSetCustomOptions()
    {
        $test = $this;

        $call = $this->call(array(
            'url' => $this->url,
            'beforeSend' => function(Call $call) use($test) {
                $test->triggeredEvents[] = 'beforeSend';
                $call->customOption = 'value';
            },
            'success' => function($data, Call $call) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $call->customOption);
            }
        ));

        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('beforeSend', 'success'));
    }

    public function testQueryDataType()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?type=query',
            'dataType' => 'query',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', $data['code']);
                $test->assertEquals('success', $data['message']);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function testJsonDataType()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'jsonObject',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data->code);
                $test->assertEquals('success', $data->message);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testGetJsonObject()
    {
        $test = $this;
        $call = $this->call->getJsonObject($this->url . '?type=json', function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals(0, $data->code);
            $test->assertEquals('success', $data->message);
        });
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testGetJson()
    {
        $test = $this;
        $call = $this->call->getJson($this->url . '?type=json', function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals(0, $data['code']);
            $test->assertEquals('success', $data['message']);
        });
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testSerializeDataType()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?type=serialize',
            'dataType' => 'serialize',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data['code']);
                $test->assertEquals('success', $data['message']);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));

        // Parse error
        $test->triggeredEvents = array();
        $call = $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'serialize',
            'error' => function($call, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertFalse($call->isSuccess());
        $this->assertCalledEvents(array('error'));
    }

    public function testXmlDataType()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?type=xml',
            'dataType' => 'xml',
            'success' => function(\SimpleXMLElement $data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', (string)$data->code);
                $test->assertEquals('success', (string)$data->message);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));

        $this->triggeredEvents = array();
        $call = $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'xml',
            'error' => function($call, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertFalse($call->isSuccess());
        $this->assertCalledEvents(array('error'));
    }

    public function testUserAgent()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => 'Test',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('Test', $data);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));

        $test->triggeredEvents = array();
        $call = $this->call(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('', $data);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testReferer()
    {
        $referer = 'http://google.com';
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?test=referer',
            'referer' => $referer,
            'success' => function($data) use($test, $referer) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals($referer, $data);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testAutoReferer()
    {
        $url = $this->url . '?test=referer';
        $test = $this;
        $call = $this->call(array(
            'url' => $url,
            'referer' => true, // Equals to current request URL
            'success' => function($data) use($test, $url) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals($url, $data);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testCookie()
    {
        $test = $this;
        $call = $this->call(array(
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
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
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
        $call = $this->call->post($this->url . '?test=post', $data, function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals('value', $data->key);
            $test->assertEquals('1', $data->post);
            $test->assertEquals('1', $data->array->{0});
            $test->assertEquals('value', $data->array->string);
        }, 'jsonObject');

        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    /**
     * @dataProvider providerForMethods
     */
    public function testMethods($method)
    {
        $test = $this;
        $call = $this->call(array(
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
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    /**
     * @dataProvider providerForGetMethods
     */
    public function testGet2($method)
    {
        $test = $this;
        $call = $this->call(array(
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
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
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
        $test = $this;
        $call = $this->call->{strtolower($method)}($this->url . '?test=methods', function($data) use($test, $method) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals($method, $data->method);
        }, 'jsonObject');
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function providerForAliasMethods()
    {
        return array(
            array('GET'),
            // array('POST'), ?
            array('DELETE'),
            array('PUT'),
            array('PATCH')
        );
    }

    public function testTimeout()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?wait=0.1',
            'dataType' => 'jsonObject',
            'timeout' => 50,
            'error' => function($call, $textStatus) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('curl', $textStatus);
            }
        ));
        $this->assertFalse($call->isSuccess());
        $this->assertCalledEvents(array('error'));
    }

    public function testGlobal()
    {
        $test = $this;
        $this->widget->setConfig('call', array(
            'method' => 'POST',
        ));

        /** @var $myCall \Widget\Call */
        $myCall = $this->widget->get('my.call');
        $call = $myCall(array(
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
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));

        $this->triggeredEvents = array();
        $call = $this->{'your.call'}(array(
            'url' => $this->url . '?test=methods',
            'dataType' => 'jsonObject',
            'global' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('GET', $data->method);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testStringAsData()
    {
        $test = $this;
        $call = $this->call(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'jsonObject',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('10', $data->number);
            }
        ));
        $this->assertTrue($call->isSuccess());
        $this->assertCalledEvents(array('success'));
    }

    public function testGetResponseJson()
    {
        $call = $this->call(array(
            'url' => $this->url . '?type=json',
            'data' => array(),
            'dataType' => 'json',
        ));

        $data = $call->getResponseJson();

        $this->assertEquals(0, $data['code']);
        $this->assertEquals('success', $data['message']);
    }

    public function testFlatApi()
    {
        // Success
        /** @var $call \Widget\Call */
        $call = $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'json'
        ));

        $this->assertTrue($call->isSuccess());

        $result = $call->getResponse();

        $this->assertInternalType('array', $result);

        // Error
        $call = $this->call(array(
            'url' => $this->url . '?code=404',
            'error' => function(){
                // overwrite the default error handler
            }
        ));
        $this->assertFalse($call->isSuccess());
        $this->assertEquals('http', $call->getErrorStatus());
        $this->assertInstanceOf('\ErrorException', $call->getErrorException());
    }

    public function testGetMethod()
    {
        $call = new \Widget\Call(array(
            'widget' => $this->widget,
            'method' => 'GET',
        ));
        $this->assertEquals('GET', $call->getMethod());

        $call = new \Widget\Call(array(
            'widget' => $this->widget,
            'method' => 'test',
        ));
        $this->assertEquals('TEST', $call->getMethod());
    }

    public function testGetIp()
    {
        $call = new \Widget\Call(array(
            'widget' => $this->widget,
            'ip' => '8.8.8.8'
        ));
        $this->assertEquals('8.8.8.8', $call->getIp());
    }

    public function testGetData()
    {
        $call = new \Widget\Call(array(
            'widget' => $this->widget,
            'data' => array(
                'key' => 'value'
            )
        ));
        $this->assertEquals(array('key' => 'value'), $call->getData());

        $call = new \Widget\Call(array(
            'widget' => $this->widget,
            'data' => 'string'
        ));
        $this->assertEquals('string', $call->getData());
    }

    public function assertCalledEvents($events)
    {
        foreach ((array)$events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }
}