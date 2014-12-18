<?php

namespace WeiTest;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForUrl
     */
    public function testUrl($result, $url, $params = array())
    {
        // Reset url to root path
        $this->request->setBaseUrl('/');

        $this->assertEquals($result, $this->url($url, $params));
    }

    public function providerForUrl()
    {
        return array(
            array(
                '/users?id=twin',
                'users?id=twin'
            ),
            array(
                '/user?id=twin',
                'user',
                array('id' => 'twin')
            ),
            array(
                '/?id=twin',
                '',
                array('id' => 'twin')
            )
        );
    }

    public function testAppend()
    {
        // Reset url to root path
        $this->request->setBaseUrl('/');

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx', array('a' => 'b', 'c' => 'd')));

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx?a=b', array('c' => 'd')));

        $this->assertEquals('xx&a=b?c=d', $this->url->append('xx&a=b', array('c' => 'd')));

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx?a=b', 'c=d'));
    }
}
