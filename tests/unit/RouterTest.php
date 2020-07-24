<?php

namespace WeiTest;

/**
 * @internal
 */
final class RouterTest extends TestCase
{
    /**
     * @var \Wei\Router
     */
    protected $object;

    public function testAddRouteWithName()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'blog(/<page>)',
        ]);

        $this->assertNotNull($router->getRoute(0));
    }

    public function testMatchStaticRoute()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'user/login',
            'defaults' => [
                'controller' => 'user',
                'action' => 'login',
            ],
        ]);

        $this->assertSame($router->match('user/login'), [
            '_id' => 0,
            'controller' => 'user',
            'action' => 'login',
        ]);
    }

    public function testMatchRequiredRoute()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'user/<action>',
            'defaults' => [
                'controller' => 'user',
            ],
        ]);

        $this->assertIsSubset([
            'controller' => 'user',
            'action' => 'login',
        ], $router->match('user/login'));
    }

    public function testMatchOptionalRoute()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'user(/<page>)',
            'defaults' => [
                'controller' => 'user',
                'page' => '1',
            ],
        ]);

        $this->assertIsSubset([
            'controller' => 'user',
            'page' => '1',
        ], $router->match('user'));

        $this->assertIsSubset([
            'controller' => 'user',
            'page' => '2',
        ], $router->match('user/2'));
    }

    public function testMatchWithRequestMethod()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set([
            'pattern' => 'postOrPut',
            'method' => 'POST|PUT',
            'defaults' => [
                'matched' => true,
            ],
        ]);

        $this->assertIsSubset([
            'matched' => true,
        ], $router->match('postOrPut'));

        $this->assertIsSubset([
            'matched' => true,
        ], $router->match('postOrPut', 'POST'));

        $this->assertFalse($router->match('postOrPut', 'GET'));
    }

    public function testNotMatchUri()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set([
            'pattern' => 'blog/(<page>)',
            'defaults' => [
                'matched' => true,
            ],
        ]);

        $this->assertFalse($router->match('withoutThisRoute'));
    }

    public function testOptionalKeyShouldReturnNull()
    {
        $router = $this->object;

        $router->set([
            'pattern' => '/blog(/<page>)(.<format>)',
            'defaults' => [
                'controller' => 'blog',
                'page' => '1',
            ],
        ]);

        $parameters = $router->match('/blog/123');

        $this->assertArrayHasKey('format', $parameters);

        $this->assertNull($parameters['format']);

        $this->assertIsSubset([
            'page' => '123',
            'controller' => 'blog',
            'format' => null,
        ], $parameters);
    }

    public function testMatchUriWithRules()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'blog/<page>',
            'rules' => [
                'page' => '\d+',
            ],
            'defaults' => [
                'matched' => true,
            ],
        ]);

        $this->assertFalse($router->match('blog/notDigits'));

        $this->assertIsSubset([
            'matched' => true,
            'page' => '1',
        ], $router->match('blog/1'));
    }

    public function testMatchWithNameParameter()
    {
        $router = $this->object;

        $router->set([
            'pattern' => '<controller>(/<action>)',
        ]);

        $this->assertSame([
            '_id' => 0,
            'controller' => 'blog/list',
            'action' => null,
        ], $router->match('blog/list'));
    }

    public function testRemove()
    {
        $router = $this->object;

        $router->set([
            'pattern' => 'blog/<page>',
        ]);

        $this->assertNotFalse($router->getRoute(0));

        $router->remove(0);

        $this->assertFalse($router->getRoute('test'));
    }

    public function testSetRoutes()
    {
        $router = $this->object;

        $router->setRoutes([
            [
                'pattern' => '/',
            ],
            [
                'pattern' => '/docs',
            ],
        ]);

        $this->assertCount(2, $router->getRoutes());
    }

    public function testStringAsRouteParameter()
    {
        $router = $this->object;

        $router->set('/blog');

        $this->assertNotEmpty($router->getRoutes());
    }

    public function testUnexpectedTypeExceptionForSetRoute()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->object->set(new \stdClass());
    }

    public function testControllerActionRoute()
    {
        $router = $this->object;

        $router->setRoutes([
            [
                'pattern' => '/(<controller>/<action>)',
                'defaults' => [
                    'controller' => 'index',
                    'action' => 'index',
                ],
            ],
            [
                'pattern' => '/<controller>',
                'defaults' => [
                    'action' => 'index',
                ],
            ],
        ]);

        $this->assertIsSubset([
            'controller' => 'posts',
            'action' => 'index',
        ], $router->match('/posts'));

        $this->assertIsSubset([
            'controller' => 'posts',
            'action' => 'add',
        ], $router->match('/posts/add'));

        $this->assertIsSubset([
            'controller' => 'admin/posts',
            'action' => 'add',
        ], $router->match('/admin/posts/add'));
    }

    public function testControllerActionIdRoute()
    {
        $router = $this->object;

        $router->setRoutes([
            '/<controller>/<action>/<id>.<format>',
            '/<controller>/<action>/<id>',
            '/<controller>/<action>',
            '/(<controller>)',
        ]);

        $params = [
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
        ];
        $this->assertIsSubset($params, $router->match('/admin/posts/edit/234'));

        $params = [
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
            'format' => 'html',
        ];
        $this->assertIsSubset($params, $router->match('/admin/posts/edit/234.html'));

        $this->assertIsSubset([
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
            'format' => 'rss',
        ], $router->match('/admin/posts/edit/234.rss'));

        $params = [
            'controller' => 'posts',
            'action' => 'edit',
            'id' => '1',
        ];
        $path = '/posts/edit/1';
        $this->assertIsSubset($params, $router->match($path));

        $params = [
            'controller' => 'posts',
            'action' => 'index',
        ];
        $path = '/posts/index';
        $this->assertIsSubset($params, $router->match($path));

        $params = [
            'controller' => 'posts',
        ];
        $path = '/posts';
        $this->assertIsSubset($params, $router->match($path));

        $this->assertIsSubset([
            'controller' => null, // $this->request('controller', 'index'); => 'index'
            'action' => null,
            'id' => null,
        ], $router->match('/'));
    }

    /**
     * @dataProvider providerForRestRoutes
     * @param mixed $method
     * @param mixed $path
     * @param mixed $params
     */
    public function testRestRoute($method, $path, $params)
    {
        $router = $this->object;

        $router->setRoutes([
            [
                'pattern' => '/<controller>/<id>/edit',
                'method' => 'GET',
                'defaults' => [
                    'action' => 'edit',
                ],
            ],
            [
                'pattern' => '/<controller>/new',
                'method' => 'GET',
                'defaults' => [
                    'action' => 'new',
                ],
            ],
            [
                'pattern' => '/<controller>/<id>',
                'method' => 'GET',
                'defaults' => [
                    'action' => 'show',
                ],
            ],
            [
                'pattern' => '/<controller>',
                'method' => 'GET',
                'defaults' => [
                    'action' => 'index',
                ],
            ],
            [
                'pattern' => '/<controller>/<id>',
                'method' => 'PATCH|PUT',
                'defaults' => [
                    'action' => 'update',
                ],
            ],
            [
                'pattern' => '/<controller>',
                'method' => 'POST',
                'defaults' => [
                    'action' => 'create',
                ],
            ],
            [
                'pattern' => '/<controller>/<id>',
                'method' => 'DELETE',
                'defaults' => [
                    'action' => 'destroy',
                ],
            ],
        ]);

        $this->assertIsSubset($params, $router->match($path, $method));
    }

    public function providerForRestRoutes()
    {
        return [
            [
                'GET',
                '/posts',
                [
                    'controller' => 'posts',
                    'action' => 'index',
                ],
            ],
            [
                'GET',
                '/posts/new',
                [
                    'controller' => 'posts',
                    'action' => 'new',
                ],
            ],
            [
                'GET',
                '/posts/123',
                [
                    'controller' => 'posts',
                    'action' => 'show',
                ],
            ],
            [
                'POST',
                '/posts',
                [
                    'controller' => 'posts',
                    'action' => 'create',
                ],
            ],
            [
                'PUT',
                '/posts/456',
                [
                    'controller' => 'posts',
                    'action' => 'update',
                    'id' => '456',
                ],
            ],
            [
                'PATCH',
                '/posts/456',
                [
                    'controller' => 'posts',
                    'action' => 'update',
                    'id' => '456',
                ],
            ],
            [
                'DELETE',
                '/posts/456',
                [
                    'controller' => 'posts',
                    'action' => 'destroy',
                    'id' => '456',
                ],
            ],
            // NOTE
            [
                'GET',
                '/admin/posts',
                [
                    'controller' => 'admin',
                    'id' => 'posts',
                ],
            ],
            // namespace controller
            [
                'GET',
                '/admin/posts/new',
                [
                    'controller' => 'admin/posts',
                    'action' => 'new',
                ],
            ],
            [
                'GET',
                '/admin/posts/456',
                [
                    'controller' => 'admin/posts',
                    'action' => 'show',
                    'id' => '456',
                ],
            ],
            [
                'POST',
                '/admin/posts',
                [
                    'controller' => 'admin/posts',
                    'action' => 'create',
                ],
            ],
            [
                'PUT',
                '/admin/posts/456',
                [
                    'controller' => 'admin/posts',
                    'action' => 'update',
                    'id' => '456',
                ],
            ],
            [
                'PATCH',
                '/admin/posts/456',
                [
                    'controller' => 'admin/posts',
                    'action' => 'update',
                    'id' => '456',
                ],
            ],
            [
                'DELETE',
                '/admin/posts/456',
                [
                    'controller' => 'admin/posts',
                    'action' => 'destroy',
                    'id' => '456',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataForMatchParamSet
     * @param mixed $method
     * @param mixed $path
     * @param mixed $routes
     * @param mixed $options
     */
    public function testMatchParamSet($method, $path, $routes, $options = [])
    {
        if ($options) {
            wei()->router->setOption($options);
        }
        $this->assertEquals(wei()->router->matchParamSet($path, $method), $routes);
    }

    public function dataForMatchParamSet()
    {
        return [
            [
                'POST',
                'admin/navs/3/links',
                [
                    [
                        'controller' => 'admin/navs',
                        'action' => 'links',
                        'id' => '3',
                    ],
                    [
                        'controller' => 'admin/navs/links',
                        'action' => 'create',
                        'navId' => '3',
                    ],
                    [
                        'controller' => 'admin/links',
                        'action' => 'create',
                        'navId' => '3',
                    ],
                ],
            ],
            // Seven basic routes
            [
                'GET',
                'photos',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'index',
                    ],
                ],
            ],
            [
                'GET',
                'photos/new',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'new',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'new',
                    ],
                ],
            ],
            [
                'POST',
                'photos',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'create',
                    ],
                ],
            ],
            [
                'GET',
                'photos/1',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'photos/1/edit',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1',
                    ],
                ],
            ],
            [
                'PATCH',
                'photos/1',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'PUT',
                'photos/1',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'DELETE',
                'photos/1',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'photos/1/comments',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'comments',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'photos/comments',
                        'action' => 'index',
                        'photoId' => '1',
                    ],
                    [
                        'controller' => 'comments',
                        'action' => 'index',
                        'photoId' => '1',
                    ],
                ],
            ],

            // ID为字符串
            [
                'GET',
                'photos/my-first-photo',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'my-first-photo',
                    ],
                ],
            ],
            [
                'GET',
                'photos/my-first-photo/edit',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => 'my-first-photo',
                    ],
                    [
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => 'my-first-photo',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => 'my-first-photo',
                    ],
                ],
            ],
            [
                'PATCH',
                'photos/my-first-photo',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => 'my-first-photo',
                    ],
                ],
            ],
            [
                'PUT',
                'photos/my-first-photo',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => 'my-first-photo',
                    ],
                ],
            ],
            [
                'DELETE',
                'photos/my-first-photo',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => 'my-first-photo',
                    ],
                ],
            ],
            [
                'GET',
                'photos/my-first-photo/comments',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'comments',
                        'id' => 'my-first-photo',
                    ],
                    [
                        // NOTICE 这里是photos/comments或comments,不是comments
                        'controller' => 'photos/comments',
                        'action' => 'index',
                        'photoId' => 'my-first-photo',
                    ],
                    [
                        'controller' => 'comments',
                        'action' => 'index',
                        'photoId' => 'my-first-photo',
                    ],
                ],
            ],

            // 单数资源(控制器单双数由自己决定,一般是双数)
            [
                'GET',
                'geocoder/new',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'new',
                    ],
                    [
                        'controller' => 'geocoder',
                        'action' => 'show',
                        'id' => 'new',
                    ],
                ],
            ],
            [
                'POST',
                'geocoder',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'create',
                    ],
                ],
            ],
            [
                'GET',
                'geocoder',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'index', // 注意此处是index,而不是show
                    ],
                ],
            ],
            [
                'GET',
                'geocoder/edit',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'edit',
                    ],
                    [
                        'controller' => 'geocoder',
                        'action' => 'show',
                        'id' => 'edit',
                    ],
                ],
            ],
            [
                'PATCH',
                'geocoder',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'update',
                    ],
                ],
            ],
            [
                'PUT',
                'geocoder',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'update',
                    ],
                ],
            ],
            [
                'DELETE',
                'geocoder',
                [
                    [
                        'controller' => 'geocoder',
                        'action' => 'destroy',
                    ],
                ],
            ],

            // 命名空间
            [
                'GET',
                'admin/articles',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'index',
                    ],
                ],
            ],
            [
                'GET',
                'admin/articles/new',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'new',
                    ],
                    [
                        'controller' => 'admin/articles',
                        'action' => 'show',
                        'id' => 'new',
                    ],
                ],
            ],
            [
                'POST',
                'admin/articles',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'create',
                    ],
                ],
            ],
            [
                'GET',
                'admin/articles/1',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'show',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'admin/articles/1/edit',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'edit',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'admin/articles/edit',
                        'action' => 'index',
                        'articleId' => '1',
                    ],
                    [
                        'controller' => 'admin/edit',
                        'action' => 'index',
                        'articleId' => '1',
                    ],
                ],
            ],
            [
                'PATCH',
                'admin/articles/1',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'update',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'PUT',
                'admin/articles/1',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'update',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'DELETE',
                'admin/articles/1',
                [
                    [
                        'controller' => 'admin/articles',
                        'action' => 'destroy',
                        'id' => '1',
                    ],
                ],
            ],

            // Nested
            [
                'GET',
                'magazines/1/ads',
                [
                    [
                        'controller' => 'magazines',
                        'action' => 'ads',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'index',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'index',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'magazines/1/ads/new',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'new',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'show',
                        'id' => 'new',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'new',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'POST',
                'magazines/1/ads',
                [
                    [
                        'controller' => 'magazines',
                        'action' => 'ads',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'create',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'create',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'magazines/1/ads/1',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'show',
                        'id' => '1',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'magazines/1/ads/1/edit',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'edit',
                        'id' => '1',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'magazines/ads/edit',
                        'action' => 'index',
                        'adId' => '1',
                        'magazineId' => '1',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'magazineId' => '1',
                        'adId' => '1',
                    ],
                ],
            ],
            [
                'PATCH',
                'magazines/1/ads/1',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'PUT',
                'magazines/1/ads/1',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                    ],
                ],
            ],
            [
                'DELETE',
                'magazines/1/ads/1',
                [
                    [
                        'controller' => 'magazines/ads',
                        'action' => 'destroy',
                        'id' => '1',
                        'magazineId' => '1',
                    ],
                ],
            ],

            // Nested, 3 levels (not recommended)
            [
                'GET',
                'publishers/1/magazines/1/ads',
                [
                    [
                        'controller' => 'publishers/magazines',
                        'action' => 'ads',
                        'id' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'index',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'index',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'publishers/1/magazines/1/ads/new',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'show',
                        'id' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'POST',
                'publishers/1/magazines/1/ads',
                [
                    [
                        'controller' => 'publishers/magazines',
                        'action' => 'ads',
                        'id' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'create',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'ads',
                        'action' => 'create',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'publishers/1/magazines/1/ads/1',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'show',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'publishers/1/magazines/1/ads/1/edit',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'edit',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'publishers/magazines/ads/edit',
                        'action' => 'index',
                        'adId' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'magazineId' => '1',
                        'adId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'PATCH',
                'publishers/1/magazines/1/ads/1',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'PUT',
                'publishers/1/magazines/1/ads/1',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],
            [
                'DELETE',
                'publishers/1/magazines/1/ads/1',
                [
                    [
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'destroy',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1',
                    ],
                ],
            ],

            // Format
            [
                'GET',
                'photos.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'index',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'GET',
                'photos/new.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'new',
                        '_format' => 'html',
                    ],
                    [
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'new',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'POST',
                'photos.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'create',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'GET',
                'photos/1.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => '1',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'GET',
                'photos/1/edit.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1',
                        '_format' => 'html',
                    ],
                    [
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'html',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'PATCH',
                'photos/1.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'PUT',
                'photos/1.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                        '_format' => 'html',
                    ],
                ],
            ],
            [
                'DELETE',
                'photos/1.html',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => '1',
                        '_format' => 'html',
                    ],
                ],
            ],

            // JSON format
            [
                'GET',
                'photos/1/edit.json',
                [
                    [
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1',
                        '_format' => 'json',
                    ],
                    [
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'json',
                    ],
                    [
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'json',
                    ],
                ],
            ],

            // 复数转换
            [
                'DELETE',
                'lotteries/1/histories/1',
                [
                    [
                        'controller' => 'lotteries/histories',
                        'action' => 'destroy',
                        'id' => '1',
                        'lotteryId' => '1',
                    ],
                ],
            ],

            // 只有命名空间
            [
                'GET',
                'admin',
                [
                    [
                        'controller' => 'admin',
                        'action' => 'index',
                    ],
                ],
            ],

            // 根路径
            [
                'GET',
                '/',
                [
                    [
                        'controller' => 'index',
                        'action' => 'index',
                    ],
                ],
            ],

            // 不支持的method
            [
                'ABC',
                '/',
                [
                    [
                        'controller' => 'index',
                        'action' => 'index',
                    ],
                ],
            ],

            // 范围
            [
                'GET',
                'user/cards/1',
                [
                    [
                        'controller' => 'user/cards',
                        'action' => 'show',
                        'id' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'user/cards/1/edit',
                [
                    [
                        'controller' => 'user/cards',
                        'action' => 'edit',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'user/cards/edit',
                        'action' => 'index',
                        'cardId' => '1',
                    ],
                    [
                        'controller' => 'user/edit',
                        'action' => 'index',
                        'cardId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'user/emails', // 当前用户的邮件地址 https://developer.github.com/v3/users/emails/
                [
                    [
                        'controller' => 'user/emails',
                        'action' => 'index',
                    ],
                ],
            ],
            [
                'GET',
                'user/products/1/transfers/add',
                [
                    [
                        'controller' => 'user/products/transfers',
                        'action' => 'add',
                        'productId' => '1',
                    ],
                    [
                        'controller' => 'user/products/transfers',
                        'action' => 'show',
                        'id' => 'add',
                        'productId' => '1',
                    ],
                    [
                        'controller' => 'user/transfers',
                        'action' => 'add',
                        'productId' => '1',
                    ],
                ],
            ],

            // 连接符,下划线转换为驼峰
            [
                'GET',
                'comic-books/new',
                [
                    [
                        'controller' => 'comicBooks',
                        'action' => 'new',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'show',
                        'id' => 'new',
                    ],
                ],
            ],
            [
                'GET',
                'comic_books/new',
                [
                    [
                        'controller' => 'comicBooks',
                        'action' => 'new',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'show',
                        'id' => 'new',
                    ],
                ],
            ],
            [
                'GET',
                'users/1/comic-books/new',
                [
                    [
                        'controller' => 'users/comicBooks',
                        'action' => 'new',
                        'userId' => '1',
                    ],
                    [
                        'controller' => 'users/comicBooks',
                        'action' => 'show',
                        'id' => 'new',
                        'userId' => '1',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'new',
                        'userId' => '1',
                    ],
                ],
            ],
            [
                'GET',
                'users/1/comic-books/sync-with-server',
                [
                    [
                        'controller' => 'users/comicBooks',
                        'action' => 'syncWithServer',
                        'userId' => '1',
                    ],
                    [
                        'controller' => 'users/comicBooks',
                        'action' => 'show',
                        'id' => 'sync-with-server',
                        'userId' => '1',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'syncWithServer',
                        'userId' => '1',
                    ],
                ],
            ],

            // Camelize param name
            [
                'GET',
                'crm-users/1/comic-books/2',
                [
                    [
                        'controller' => 'crmUsers/comicBooks',
                        'action' => 'show',
                        'id' => '2',
                        'crmUserId' => '1',
                    ],
                ],
            ],

            [
                'GET',
                'crm-users/1/comic-books',
                [
                    [
                        'controller' => 'crmUsers',
                        'action' => 'comicBooks',
                        'id' => '1',
                    ],
                    [
                        'controller' => 'crmUsers/comicBooks',
                        'action' => 'index',
                        'crmUserId' => '1',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'index',
                        'crmUserId' => '1',
                    ],
                ],
            ],

            [
                'GET',
                'crm-users/1/comic-books/new',
                [
                    [
                        'controller' => 'crmUsers/comicBooks',
                        'action' => 'new',
                        'crmUserId' => '1',
                    ],
                    [
                        'controller' => 'crmUsers/comicBooks',
                        'action' => 'show',
                        'id' => 'new',
                        'crmUserId' => '1',
                    ],
                    [
                        'controller' => 'comicBooks',
                        'action' => 'new',
                        'crmUserId' => '1',
                    ],
                ],
            ],

            // Combined resources
            [
                'GET',
                'issues/comments',
                [
                    [
                        'controller' => 'issues/comments',
                        'action' => 'index',
                    ],
                ],
                [
                    'combinedResources' => ['issues/comments'],
                ],
            ],
            [
                'GET',
                'issues/comments/1',
                [
                    [
                        'controller' => 'issues/comments',
                        'action' => 'show',
                        'id' => '1',
                    ],
                ],
                [
                    'combinedResources' => ['issues/comments'],
                ],
            ],
            [
                'PUT',
                'issues/comments/1',
                [
                    [
                        'controller' => 'issues/comments',
                        'action' => 'update',
                        'id' => '1',
                    ],
                ],
                [
                    'combinedResources' => ['issues/comments'],
                ],
            ],
            [
                'GET',
                'users/twinh/repos/wei/issues/comments/1',
                [
                    [
                        'controller' => 'users/repos/issues/comments',
                        'action' => 'show',
                        'id' => '1',
                        'userId' => 'twinh',
                        'repoId' => 'wei',
                    ],
                ],
                [
                    'combinedResources' => ['issues/comments'],
                ],
            ],
            // Combined resources with format
            [
                'GET',
                'issues/comments.json',
                [
                    [
                        'controller' => 'issues/comments',
                        'action' => 'index',
                        '_format' => 'json',
                    ],
                ],
                [
                    'combinedResources' => ['issues/comments'],
                ],
            ],
        ];
    }

    /**
     * @link http://zh.wiktionary.org/zh/%E9%99%84%E5%BD%95:%E8%8B%B1%E8%AF%AD%E4%B8%8D%E8%A7%84%E5%88%99%E5%A4%8D%E6%95%B0
     * @link https://github.com/doctrine/inflector/blob/master/tests/Doctrine/Tests/Common/Inflector/InflectorTest.php
     */
    public function dataForSingularize()
    {
        return [
            ['life', 'lives'],

            ['man', 'men'],

            ['child', 'children'],

            ['auto', 'autos'],
            ['memo', 'memos'],
            ['photo', 'photos'],
            ['piano', 'pianos'],
            ['pro', 'pros'],
            ['solo', 'solos'],
            ['studio', 'studios'],
            ['tattoo', 'tattoos'],
            ['video', 'videos'],
            ['zoo', 'zoos'],

            ['echo', 'echoes'],
            ['hero', 'heroes'],
            ['potato', 'potatoes'],
            ['tomato', 'tomatoes'],

            ['zero', 'zeros'],
            ['zero', 'zeroes'],

            ['deer', 'deer'],
            ['fish', 'fish'],
            ['sheep', 'sheep'],

            ['formula', 'formulas'],

            ['datum', 'data'],
            ['analysis', 'analyses'],
            ['money', 'monies'],
            ['move', 'moves'],
            ['sex', 'sexes'],
            ['human', 'humans'],

            ['appendix', 'appendixes'],
            ['index', 'indexes'],
            ['matrix', 'matrixes'],

            ['history', 'histories'],
            ['information', 'information'],

            ['categoria', 'categorias'],
            ['house', 'houses'],
            ['bus', 'buses'],
            ['menu', 'menus'],
            ['news', 'news'],
            ['quiz', 'quizzes'],
            ['matrix_row', 'matrix_rows'],
            ['matrix', 'matrices'],
            ['alias', 'aliases'],
            ['Media', 'Media'],
            ['person', 'people'],
            ['glove', 'gloves'],
            ['wave', 'waves'],
            ['cafe', 'cafes'],
            ['roof', 'roofs'],
            ['cookie', 'cookies'],
            ['identity', 'identities'],
            ['criterion', 'criteria'],
            ['', ''],
        ];
    }

    /**
     * @dataProvider dataForSingularize
     * @param mixed $singular
     * @param mixed $plural
     */
    public function testSingularize($singular, $plural)
    {
        $router = wei()->router;
        $method = new \ReflectionMethod($router, 'singularize');
        $method->setAccessible(true);
        $this->assertEquals($singular, $method->invoke($router, $plural));
    }

    protected function getRouterOutput()
    {
        ob_start();
        call_user_func_array($this->object, func_get_args());
        return ob_get_clean();
    }
}
