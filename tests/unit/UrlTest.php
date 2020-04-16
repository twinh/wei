<?php

namespace WeiTest;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForTo
     */
    public function testTo($result, $url, $params = array())
    {
        // Reset url to root path
        $this->request->setBaseUrl('/');

        $this->assertEquals($result, $this->url->to($url, $params));
    }

    public function providerForTo()
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

    public function testQueryUrl()
    {
        $this->request->setBaseUrl('/');

        $gets = &$this->request->getQueries();
        $gets = array('a' => 'b');

        $this->assertEquals('/articles?a=b', $this->url->query('articles'));

        $this->assertEquals('/articles?c=d&a=b', $this->url->query('articles', array('c' => 'd')));

        $this->assertEquals('/articles/1?a=b', $this->url->query('articles/%s', 1));

        $this->assertEquals('/articles/1?c=d&a=b', $this->url->query('articles/%s', 1, array('c' => 'd')));
    }

    public function testInvoke()
    {
        $this->request->setBaseUrl('/');

        $this->assertSame('/test', $this->url('test'));
    }
}
