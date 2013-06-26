<?php

namespace WidgetTest\Validator;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForUrl
     */
    public function testUrl($input, $options = array())
    {
        $this->assertTrue($this->is('url', $input, $options));
        
        $this->assertFalse($this->is('notUrl', $input, $options));
    }

    /**
     * @dataProvider providerForNotUrl
     */
    public function testNotUrl($input, $options = array())
    {
        $this->assertFalse($this->is('url', $input, $options));
        
        $this->assertTrue($this->is('notUrl', $input, $options));
    }

    public function providerForUrl()
    {
        return array(
            array('http://www.google.com'),
            array('http://example.com'),
            array('http://exa-mple.com'),
            array('file:///tmp/test.c'),
            array('ftp://ftp.example.com/tmp/'),
            array('http://qwe'),
            array('https://www.example.com/', array(
                'path' => true
            )),
            array('https://127.0.0.1/', array(
                'path' => true
            )),
            array('http://www.example.com/index.html?q=123', array(
                'query' => true 
            ))
        );
    }

    public function providerForNotUrl()
    {
        return array(
            array('http://exa_mple.com'),
            array('g.cn'),
            array('http//www.example'),
            array('http:/www.example'),
            array('/tmp/test.c'),
            array('/'),
            array("http://\r\n/bar"),
            array('https://localhost', array(
                'path' => true
            )),
            array('https://localhost', array(
                'query' => true
            ))
        );
    }
}
