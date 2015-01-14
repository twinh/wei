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

    public function testFormat()
    {
        // Reset url to root path
        $this->request->setBaseUrl('/');

        $this->assertEquals('/articles/1', $this->url('articles/%s', 1));

        $this->assertEquals('/articles/1/comments/2', $this->url('articles/%s/comments/%s', array(1, 2)));

        $this->assertEquals('/articles/1/comments/2?a=b', $this->url('articles/%s/comments/%s', array(1, 2), array('a' => 'b')));

        $this->assertEquals('/articles/b/comments/d', $this->url('articles/%s/comments/%s', array('a' => 'b', 'c' => 'd')));

        $this->assertEquals('/articles/b/comments/d', $this->url('articles/%s/comments/%s', array('a' => 'b', 'c' => 'd', 'e' => 'f')));
    }

    public function testFormatError()
    {
        $this->setExpectedException('PHPUnit_Framework_Error_Warning', 'vsprintf(): Too few arguments');
        $this->assertEquals('/articles/b/comments/d', $this->url('articles/%s/comments/%s', array('a' => 'b')));
    }
}
