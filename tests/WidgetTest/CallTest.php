<?php

namespace WidgetTest;

use Widget\Call;

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

        $this->url = $this->call->getOption('url');

        // TODO how to test in cli
        if (false === @fopen($this->url, 'r')) {
            $this->markTestSkipped(sprintf('Url %s is not available', $this->url));
        }

        $this->call->setOption('error', function($call, $type, $e){
            throw $e;
        });
    }

    /**
     * @dataProvider providerForSuccess
     */
    public function testSuccess($options)
    {
        $test = $this;
        $this->call(array(
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

        $this->call(array(
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
        $this->call(array(
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

                $test->assertEquals('Value', $data->KEY);
                $test->assertEquals('Value with space', $data->KEY_NAME);
            }
        ));
    }

    public function testLateBindingCallbacks()
    {
        $test = $this;

        $this->call(array(
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

        $this->call(array(
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
        $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'json',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data->code);
                $test->assertEquals('success', $data->message);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function testGetJson()
    {
        $test = $this;
        $this->call->getJson($this->url . '?type=json', function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals(0, $data->code);
            $test->assertEquals('success', $data->message);
        });
        $this->assertCalledEvents(array('success'));
    }

    public function testSerializeDataType()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?type=serialize',
            'dataType' => 'serialize',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(0, $data['code']);
                $test->assertEquals('success', $data['message']);
            }
        ));
        $this->assertCalledEvents(array('success'));

        // Parse error
        $test->triggeredEvents = array();
        $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'serialize',
            'error' => function($call, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertCalledEvents(array('error'));
    }

    public function testXmlDataType()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?type=xml',
            'dataType' => 'xml',
            'success' => function(\SimpleXMLElement $data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('0', (string)$data->code);
                $test->assertEquals('success', (string)$data->message);
            }
        ));
        $this->assertCalledEvents(array('success'));

        $this->triggeredEvents = array();
        $this->call(array(
            'url' => $this->url . '?type=json',
            'dataType' => 'xml',
            'error' => function($call, $textStatus, $exception) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('parser', $textStatus);
                $test->assertInstanceOf('\ErrorException', $exception);
            }
        ));
        $this->assertCalledEvents(array('error'));
    }

    public function testUserAgent()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => 'Test',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('Test', $data);
            }
        ));
        $this->assertCalledEvents(array('success'));

        $test->triggeredEvents = array();
        $this->call(array(
            'url' => $this->url . '?test=user-agent',
            'userAgent' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('', $data);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function testReferer()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=referer',
            'referer' => 'Sent from my iPhone',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('Sent from my iPhone', $data);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function testCookie()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=cookie',
            'dataType' => 'json',
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
        $this->call->post($this->url . '?test=post', $data, function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals('value', $data->key);
            $test->assertEquals('1', $data->post);
            $test->assertEquals('1', $data->array->{0});
            $test->assertEquals('value', $data->array->string);
        }, 'json');

        $this->assertCalledEvents(array('success'));
    }

    /**
     * @dataProvider providerForMethods
     */
    public function testMethods($method)
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=methods',
            'method' => $method,
            'dataType' => 'json',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test, $method) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals(strtoupper($method), $data->method);
                $test->assertEquals('v', $data->data->k);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    /**
     * @dataProvider providerForGetMethods
     */
    public function testGet($method)
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=get',
            'method' => $method,
            'dataType' => 'json',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('v', $data->k);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function providerForGetMethods()
    {
        return array(
            array('GET'),
            ///array('HEAD'), no content
            //array('TRACE'), Method Not Allowed
            array('OPTIONS'),
            // array('CONNECT'), Bad Request
            array('CUSTOM')
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
        $this->call->{strtolower($method)}($this->url . '?test=methods', function($data) use($test, $method) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals($method, $data->method);
        }, 'json');
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
        $this->call(array(
            'url' => $this->url . '?wait=0.1',
            'dataType' => 'json',
            'timeout' => 50,
            'error' => function($call, $textStatus) use($test) {
                $test->triggeredEvents[] = 'error';
                $test->assertEquals('curl', $textStatus);
            }
        ));
        $this->assertCalledEvents(array('error'));
    }

    public function testGlobal()
    {
        $test = $this;
        $this->call->setMethod('POST');

        $this->call(array(
            'url' => $this->url . '?test=methods',
            'global' => true,
            'dataType' => 'json',
            'data' => array(
                'k' => 'v'
            ),
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('POST', $data->method);
            }
        ));
        $this->assertCalledEvents(array('success'));

        $this->triggeredEvents = array();
        $this->call(array(
            'url' => $this->url . '?test=methods',
            'dataType' => 'json',
            'global' => false,
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('GET', $data->method);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function testStringAsData()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . '?test=get',
            'data' => 'key=value&number=10',
            'dataType' => 'json',
            'success' => function($data) use($test) {
                $test->triggeredEvents[] = 'success';
                $test->assertEquals('value', $data->key);
                $test->assertEquals('10', $data->number);
            }
        ));
        $this->assertCalledEvents(array('success'));
    }

    public function assertCalledEvents($events)
    {
        foreach ((array)$events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }
}