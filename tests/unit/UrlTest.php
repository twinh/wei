<?php

namespace WeiTest;

use Wei\Req;
use Wei\Url;

/**
 * @internal
 */
final class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForTo
     * @param mixed $result
     * @param mixed $url
     * @param mixed $params
     */
    public function testTo($result, $url, $params = [])
    {
        // Reset url to root path
        $this->req->setBaseUrl('/');

        $this->assertEquals($result, $this->url->to($url, $params));
    }

    public function providerForTo()
    {
        return [
            [
                '/users?id=twin',
                'users?id=twin',
            ],
            [
                '/user?id=twin',
                'user',
                ['id' => 'twin'],
            ],
            [
                '/?id=twin',
                '',
                ['id' => 'twin'],
            ],
        ];
    }

    public function testAppend()
    {
        // Reset url to root path
        $this->req->setBaseUrl('/');

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx', ['a' => 'b', 'c' => 'd']));

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx?a=b', ['c' => 'd']));

        $this->assertEquals('xx&a=b?c=d', $this->url->append('xx&a=b', ['c' => 'd']));

        $this->assertEquals('xx?a=b&c=d', $this->url->append('xx?a=b', 'c=d'));
    }

    public function testFormat()
    {
        // Reset url to root path
        $this->req->setBaseUrl('/');

        $this->assertEquals('/articles/1', $this->url('articles/%s', 1));

        $this->assertEquals('/articles/1/comments/2', $this->url('articles/%s/comments/%s', [1, 2]));

        $this->assertEquals(
            '/articles/1/comments/2?a=b',
            $this->url('articles/%s/comments/%s', [1, 2], ['a' => 'b'])
        );

        $this->assertEquals(
            '/articles/b/comments/d',
            $this->url('articles/%s/comments/%s', ['a' => 'b', 'c' => 'd'])
        );

        $this->assertEquals(
            '/articles/b/comments/d',
            $this->url('articles/%s/comments/%s', ['a' => 'b', 'c' => 'd', 'e' => 'f'])
        );
    }

    public function testQueryUrl()
    {
        $this->req->setBaseUrl('/');

        $gets = &$this->req->getQueries();
        $gets = ['a' => 'b'];

        $this->assertEquals('/articles?a=b', $this->url->query('articles'));

        $this->assertEquals('/articles?c=d&a=b', $this->url->query('articles', ['c' => 'd']));

        $this->assertEquals('/articles/1?a=b', $this->url->query('articles/%s', 1));

        $this->assertEquals('/articles/1?c=d&a=b', $this->url->query('articles/%s', 1, ['c' => 'd']));
    }

    public function testInvoke()
    {
        $this->req->setBaseUrl('/');

        $this->assertSame('/test', $this->url('test'));
    }

    /**
     * @param array $options
     * @param string $to
     * @param array $argOrParams
     * @param array $params
     * @param string $result
     * @dataProvider providerForUrlRewrite
     */
    public function testUrlRewrite(array $options, string $to, array $argOrParams, array $params, string $result)
    {
        $request = new Req(['wei' => $this->wei, 'fromGlobal' => false] + $options);
        $url = new Url(['wei' => $this->wei, 'req' => $request]);

        $this->assertSame($result, $url->to($to, $argOrParams, $params));
    }

    public function providerForUrlRewrite()
    {
        return [
            [
                [
                    'defaultUrlRewrite' => true,
                    'data' => [
                        'r' => 'test',
                    ],
                ],
                'users?id=twin',
                [],
                [],
                '/users?id=twin',
            ],
            [
                [
                    'defaultUrlRewrite' => false,
                    'data' => [
                        'r' => 'test',
                    ],
                ],
                'user',
                ['id' => 'twin'],
                [],
                '/?r=user&id=twin',
            ],
            [
                [
                    'defaultUrlRewrite' => false,
                ],
                '',
                ['id' => 'twin'],
                [],
                '/?id=twin',
            ],
            [
                [
                    'defaultUrlRewrite' => false,
                ],
                'users/%s',
                [1],
                [],
                '/?r=users%2F1',
            ],
            [
                [
                    'defaultUrlRewrite' => false,
                ],
                'users/%s',
                [1],
                ['key' => 'value'],
                '/?r=users%2F1&key=value',
            ],
        ];
    }
}
