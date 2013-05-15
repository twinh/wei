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

        $this->call->setOption('error', function($call, $type){
            throw new \Exception('An error occurred: ' . $type);
        });
    }

    /**
     * @dataProvider providerForSuccess
     */
    public function testSuccess($options)
    {
        $test = $this;
        $this->call(array(
            'dataType' => 'raw',
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
                'url' => $url . 'url.php'
            )),
            array(array(
                'url' => $url . 'url.php?abc=cdf',
            )),
            array(array(
                'url' => $url . 'url.php#abc'
            )),
            array(array(
                'url' => $url . 'url.php?abc#abc'
            ))
         );
    }

    public function testUrlAndOptionsSyntax()
    {
        $test = $this;
        $this->call($this->url . 'url.php', array(
            'dataType' => 'raw',
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
                'url' => $url . 'url.php?code=404'
            ), 'default text'),
            array(array(
                'url' => $url . 'url.php?code=500'
            ), 'default text'),
            // Couldn't resolve host '404.php.net'
            array(array(
                'url' => 'http://404.php.net/'
            ), null),
        );
    }

    public function testHeaders()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . 'url.php?test=headers',
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
            'url' => $this->url . 'url.php',
            'dataType' => 'raw',
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
            'url' => $this->url . 'url.php',
            'dataType' => 'raw',
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
            'url' => $this->url . 'url.php',
            'dataType' => 'raw',
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

    public function testJson()
    {
        $test = $this;

        $this->call(array(
            'url' => $this->url . 'url.php?type=json',
            'dataType' => 'json',
            'success' => function($data, $ch) use($test) {
                //$test->assertInstanceOf('stdClass', $data);
                //$test->assertInternalType('resource', $ch);
            },
            'complete' => function($data) {

            }
        ));
    }

    public function testCall()
    {
//        $this->call(array(
//            'dataType' => 'json'
//        ));
    }

    public function testGet()
    {

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
        $this->call->post($this->url . 'url.php?test=post', $data, function($data) use($test) {
            $test->triggeredEvents[] = 'success';
            $test->assertEquals('value', $data->key);
            $test->assertEquals('1', $data->post);
            $test->assertEquals('1', $data->array->{0});
            $test->assertEquals('value', $data->array->string);
        });

        $this->assertCalledEvents(array('success'));
    }

    public function testSoap()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . 'soap.php',
            'type' => 'soap',
            'method' => 'add',
            'wsdl' => false,
            'dataType' => 'raw',
            'success' => function($data) use($test) {

            },
            'error' => function(){

            }
        ));
    }

    public function assertCalledEvents($events)
    {
        foreach ((array)$events as $event) {
            $this->assertContains($event, $this->triggeredEvents);
        }
    }
}