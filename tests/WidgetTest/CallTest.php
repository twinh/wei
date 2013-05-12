<?php

namespace WidgetTest;

class CallTest extends TestCase
{
    /**
     * The basic URL for call widget
     *
     * @var string
     */
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->url = $this->call->getOption('url');

        // TODO how to test in cli
        if (false === @fopen($this->url)) {
            $this->markTestSkipped(sprintf('Url %s is not available', $this->url));
        }
    }

    public function testSuccess()
    {
        $test = $this;

        $this->call(array(
            'url' => $this->url . 'url.php?type=json',
            'dataType' => 'json',
            'success' => function($data, $ch) use($test) {
                $test->assertInstanceOf('stdClass', $data);
                $test->assertInternalType('resource', $ch);
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

    }

    public function testSoap()
    {
        $test = $this;
        $this->call(array(
            'url' => $this->url . 'soap.php',
            'type' => 'soap',
            'method' => 'helloWorld',
            'wsdl' => false,
            'dataType' => 'raw',
            'success' => function($data) use($test) {

            },
            'error' => function(){

            }
        ));
    }
}