<?php

namespace WidgetTest\Validator;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForUrl
     */
    public function testUrl($input)
    {
        $this->assertTrue($this->is('url', $input));
        
        $this->assertFalse($this->is('notUrl', $input));
    }

    /**
     * @dataProvider providerForNotUrl
     */
    public function testNotUrl($input)
    {
        $this->assertFalse($this->is('url', $input));
        
        $this->assertTrue($this->is('notUrl', $input));
    }

    public function providerForUrl()
    {
        return array(
            array('http://www.google.com'),
            array('http://example.com'),
            array('http://exa-mple.com'),
        );
    }

    public function providerForNotUrl()
    {
        return array(
            array('http://exa_mple.com'),
            array('g.cn')
        );
    }
}
