<?php

namespace WeiTest;

class RouterTest extends TestCase
{
    /**
     * @var \Wei\Router
     */
    protected $object;

    public function testAddRouteWithName()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog(/<page>)',
        ));

        $this->assertNotNull($router->getRoute(0));
    }

    public function testMatchStaticRoute()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user/login',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertIsSubset($router->match('user/login'), array(
            'controller' => 'user',
            'action' => 'login'
        ));
    }

    public function testMatchRequiredRoute()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user/<action>',
            'defaults' => array(
                'controller' => 'user',
            ),
        ));

        $this->assertIsSubset(array(
            'controller' => 'user',
            'action' => 'login',
        ), $router->match('user/login'));
    }

    public function testMatchOptionalRoute()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user(/<page>)',
            'defaults' => array(
                'controller' => 'user',
                'page' => '1'
            ),
        ));

        $this->assertIsSubset(array(
            'controller' => 'user',
            'page' => '1',
        ), $router->match('user'));

        $this->assertIsSubset(array(
            'controller' => 'user',
            'page' => '2',
        ), $router->match('user/2'));
    }

    public function testMatchWithRequestMethod()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'pattern' => 'postOrPut',
            'method' => 'POST|PUT',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertIsSubset(array(
            'matched' => true,
        ), $router->match('postOrPut'));

        $this->assertIsSubset(array(
            'matched' => true
        ), $router->match('postOrPut', 'POST'));

        $this->assertFalse($router->match('postOrPut', 'GET'));
    }

    public function testNotMatchUri()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'pattern' => 'blog/(<page>)',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertFalse($router->match('withoutThisRoute'));
    }

    public function testOptionalKeyShouldReturnNull()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => '/blog(/<page>)(.<format>)',
            'defaults' => array(
                'controller' => 'blog',
                'page' => '1'
            ),
        ));

        $parameters = $router->match('/blog/123');

        $this->assertArrayHasKey('format', $parameters);

        $this->assertNull($parameters['format']);

        $this->assertIsSubset(array(
            'page' => '123',
            'controller' => 'blog',
            'format' => null
        ), $parameters);
    }

    public function testMatchUriWithRules()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog/<page>',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertFalse($router->match('blog/notDigits'));

        $this->assertIsSubset(array(
            'matched' => true,
            'page' => '1',
        ), $router->match('blog/1'));
    }

    public function testMatchWithNameParameter()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => '<controller>(/<action>)'
        ));

        $this->assertIsSubset(array(
            'controller' => 'blog',
            'action' => 'list'
        ), $router->match('blog/list', null, 'default'));
    }

    public function testRemove()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog/<page>'
        ));

        $this->assertNotEquals(false, $router->getRoute(0));

        $router->remove(0);

        $this->assertFalse($router->getRoute('test'));
    }

    public function testSetRoutes()
    {
        $router = $this->object;

        $router->setRoutes(array(
            array(
                'pattern' => '/'
            ),
            array(
                'pattern' => '/docs'
            ),
        ));

        $this->assertCount(2, $router->getRoutes());
    }

    public function testStringAsRouteParameter()
    {
        $router = $this->object;

        $router->set('/blog');

        $this->assertNotEmpty($router->getRoutes());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexpectedTypeExceptionForSetRoute()
    {
        $this->object->set(new \stdClass());
    }

    public function testControllerActionRoute()
    {
        $router = $this->object;

        $router->setRoutes(array(
            array(
                'pattern' => '/(<controller>/<action>)',
                'defaults' => array(
                    'controller' => 'index',
                    'action' => 'index'
                )
            ),
            array(
                'pattern' => '/<controller>',
                'defaults' => array(
                    'action' => 'index'
                )
            )
        ));

        $this->assertIsSubset(array(
            'controller' => 'posts',
            'action' => 'index'
        ), $router->match('/posts'));

        $this->assertIsSubset(array(
            'controller' => 'posts',
            'action' => 'add'
        ), $router->match('/posts/add'));

        $this->assertIsSubset(array(
            'controller' => 'admin/posts',
            'action' => 'add'
        ), $router->match('/admin/posts/add'));
    }

    public function testControllerActionIdRoute()
    {
        $router = $this->object;

        $router->setRoutes(array(
            '/<controller>/<action>/<id>.<format>',
            '/<controller>/<action>/<id>',
            '/<controller>/<action>',
            '/(<controller>)'
        ));

        $params = array(
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234'
        );
        $this->assertIsSubset($params, $router->match('/admin/posts/edit/234'));

        $params = array(
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
            'format' => 'html'
        );
        $this->assertIsSubset($params, $router->match('/admin/posts/edit/234.html'));

        $this->assertIsSubset(array(
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
            'format' => 'rss'
        ), $router->match('/admin/posts/edit/234.rss'));

        $params = array(
            'controller' => 'posts',
            'action' => 'edit',
            'id' => '1'
        );
        $path = '/posts/edit/1';
        $this->assertIsSubset($params, $router->match($path));

        $params = array(
            'controller' => 'posts',
            'action' => 'index',
        );
        $path = '/posts/index';
        $this->assertIsSubset($params, $router->match($path));

        $params = array(
            'controller' => 'posts',
        );
        $path = '/posts';
        $this->assertIsSubset($params, $router->match($path));

        $this->assertIsSubset(array(
            'controller' => null, // $this->request('controller', 'index'); => 'index'
            'action' => null,
            'id' => null
        ), $router->match('/'));
    }

    /**
     * @dataProvider providerForRestRoutes
     */
    public function testRestRoute($method, $path, $params)
    {
        $router = $this->object;

        $router->setRoutes(array(
            array(
                'pattern' => '/<controller>/<id>/edit',
                'method' => 'GET',
                'defaults' => array(
                    'action' => 'edit'
                )
            ),
            array(
                'pattern' => '/<controller>/new',
                'method' => 'GET',
                'defaults' => array(
                    'action' => 'new'
                )
            ),
            array(
                'pattern' => '/<controller>/<id>',
                'method' => 'GET',
                'defaults' => array(
                    'action' => 'show'
                )
            ),
            array(
                'pattern' => '/<controller>',
                'method' => 'GET',
                'defaults' => array(
                    'action' => 'index'
                )
            ),
            array(
                'pattern' => '/<controller>/<id>',
                'method' => 'PATCH|PUT',
                'defaults' => array(
                    'action' => 'update'
                )
            ),
            array(
                'pattern' => '/<controller>',
                'method' => 'POST',
                'defaults' => array(
                    'action' => 'create'
                )
            ),
            array(
                'pattern' => '/<controller>/<id>',
                'method' => 'DELETE',
                'defaults' => array(
                    'action' => 'destroy'
                )
            )
        ));

        $this->assertIsSubset($params, $router->match($path, $method));
    }

    public function providerForRestRoutes()
    {
        return array(
            array(
                'GET',
                '/posts',
                array(
                    'controller' => 'posts',
                    'action' => 'index'
                )
            ),
            array(
                'GET',
                '/posts/new',
                array(
                    'controller' => 'posts',
                    'action' => 'new'
                )
            ),
            array(
                'GET',
                '/posts/123',
                 array(
                     'controller' => 'posts',
                     'action' => 'show'
                 )
            ),
            array(
                'POST',
                '/posts',
                array(
                    'controller' => 'posts',
                    'action' => 'create'
                )
            ),
            array(
                'PUT',
                '/posts/456',
                array(
                    'controller' => 'posts',
                    'action' => 'update',
                    'id' => '456'
                ),
            ),
            array(
                'PATCH',
                '/posts/456',
                array(
                    'controller' => 'posts',
                    'action' => 'update',
                    'id' => '456'
                ),
            ),
            array(
                'DELETE',
                '/posts/456',
                array(
                    'controller' => 'posts',
                    'action' => 'destroy',
                    'id' => '456'
                )
            ),
            // NOTE
            array(
                'GET',
                '/admin/posts',
                array(
                    'controller' => 'admin',
                    'id' => 'posts'
                )
            ),
            // namespace controller
            array(
                'GET',
                '/admin/posts/new',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'new'
                )
            ),
            array(
                'GET',
                '/admin/posts/456',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'show',
                    'id' => '456'
                )
            ),
            array(
                'POST',
                '/admin/posts',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'create'
                )
            ),
            array(
                'PUT',
                '/admin/posts/456',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'update',
                    'id' => '456'
                ),
            ),
            array(
                'PATCH',
                '/admin/posts/456',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'update',
                    'id' => '456'
                ),
            ),
            array(
                'DELETE',
                '/admin/posts/456',
                array(
                    'controller' => 'admin/posts',
                    'action' => 'destroy',
                    'id' => '456'
                )
            ),
        );
    }

    protected function getRouterOutput()
    {
        ob_start();
        call_user_func_array($this->object, func_get_args());
        return ob_get_clean();
    }


    /**
     * @dataProvider dataForMatchParamSet
     */
    public function testMatchParamSet($method, $path, $routes, $options = array())
    {
        if ($options) {
            wei()->router->setOption($options);
        }
        $this->assertEquals(wei()->router->matchParamSet($path, $method), $routes);
    }

    public function dataForMatchParamSet()
    {
        return array(
            array(
                'POST',
                'admin/navs/3/links',
                array(
                    array(
                        'controller' => 'admin/navs',
                        'action' => 'links',
                        'id' => '3'
                    ),
                    array(
                        'controller' => 'admin/navs/links',
                        'action' => 'create',
                        'navId' => '3'
                    ),
                    array(
                        'controller' => 'admin/links',
                        'action' => 'create',
                        'navId' => '3'
                    )
                )
            ),
            // Seven basic routes
            array(
                'GET',
                'photos',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'index'
                    )
                )
            ),
            array(
                'GET',
                'photos/new',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'new'
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'new'
                    ),
                )
            ),
            array(
                'POST',
                'photos',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'create'
                    )
                )
            ),
            array(
                'GET',
                'photos/1',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => '1'
                    ),
                )
            ),
            array(
                'GET',
                'photos/1/edit',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1'
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1'
                    )
                )
            ),
            array(
                'PATCH',
                'photos/1',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1'
                    )
                )
            ),
            array(
                'PUT',
                'photos/1',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1'
                    )
                )
            ),
            array(
                'DELETE',
                'photos/1',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => '1'
                    )
                )
            ),
            array(
                'GET',
                'photos/1/comments',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'comments',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'photos/comments',
                        'action' => 'index',
                        'photoId' => '1'
                    ),
                    array(
                        'controller' => 'comments',
                        'action' => 'index',
                        'photoId' => '1'
                    )
                )
            ),

            // ID为字符串
            array(
                'GET',
                'photos/my-first-photo',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'my-first-photo'
                    ),
                )
            ),
            array(
                'GET',
                'photos/my-first-photo/edit',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => 'my-first-photo'
                    ),
                    array(
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => 'my-first-photo'
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => 'my-first-photo'
                    )
                )
            ),
            array(
                'PATCH',
                'photos/my-first-photo',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => 'my-first-photo'
                    )
                )
            ),
            array(
                'PUT',
                'photos/my-first-photo',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => 'my-first-photo'
                    )
                )
            ),
            array(
                'DELETE',
                'photos/my-first-photo',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'myFirstPhoto',
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => 'my-first-photo'
                    )
                )
            ),
            array(
                'GET',
                'photos/my-first-photo/comments',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'comments',
                        'id' => 'my-first-photo'
                    ),
                    array(
                        // NOTICE 这里是photos/comments或comments,不是comments
                        'controller' => 'photos/comments',
                        'action' => 'index',
                        'photoId' => 'my-first-photo'
                    ),
                    array(
                        'controller' => 'comments',
                        'action' => 'index',
                        'photoId' => 'my-first-photo'
                    )
                )
            ),

            // 单数资源(控制器单双数由自己决定,一般是双数)
            array(
                'GET',
                'geocoder/new',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'new'
                    ),
                    array(
                        'controller' => 'geocoder',
                        'action' => 'show',
                        'id' => 'new'
                    ),
                )
            ),
            array(
                'POST',
                'geocoder',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'create'
                    )
                )
            ),
            array(
                'GET',
                'geocoder',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'index' // 注意此处是index,而不是show
                    )
                )
            ),
            array(
                'GET',
                'geocoder/edit',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'edit'
                    ),
                    array(
                        'controller' => 'geocoder',
                        'action' => 'show',
                        'id' => 'edit'
                    )
                )
            ),
            array(
                'PATCH',
                'geocoder',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'update'
                    )
                )
            ),
            array(
                'PUT',
                'geocoder',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'update'
                    )
                )
            ),
            array(
                'DELETE',
                'geocoder',
                array(
                    array(
                        'controller' => 'geocoder',
                        'action' => 'destroy'
                    )
                )
            ),

            // 命名空间
            array(
                'GET',
                'admin/articles',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'index',
                    )
                )
            ),
            array(
                'GET',
                'admin/articles/new',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'new'
                    ),
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'show',
                        'id' => 'new'
                    ),
                )
            ),
            array(
                'POST',
                'admin/articles',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'create'
                    )
                )
            ),
            array(
                'GET',
                'admin/articles/1',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'show',
                        'id' => '1'
                    ),
                )
            ),
            array(
                'GET',
                'admin/articles/1/edit',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'edit',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'admin/articles/edit',
                        'action' => 'index',
                        'articleId' => '1'
                    ),
                    array(
                        'controller' => 'admin/edit',
                        'action' => 'index',
                        'articleId' => '1'
                    )
                )
            ),
            array(
                'PATCH',
                'admin/articles/1',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'update',
                        'id' => '1'
                    )
                )
            ),
            array(
                'PUT',
                'admin/articles/1',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'update',
                        'id' => '1'
                    )
                )
            ),
            array(
                'DELETE',
                'admin/articles/1',
                array(
                    array(
                        'controller' => 'admin/articles',
                        'action' => 'destroy',
                        'id' => '1'
                    )
                )
            ),

            // Nested
            array(
                'GET',
                'magazines/1/ads',
                array(
                    array(
                        'controller' => 'magazines',
                        'action' => 'ads',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'index',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'index',
                        'magazineId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'magazines/1/ads/new',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'new',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'show',
                        'id' => 'new',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'new',
                        'magazineId' => '1',
                    )
                )
            ),
            array(
                'POST',
                'magazines/1/ads',
                array(
                    array(
                        'controller' => 'magazines',
                        'action' => 'ads',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'create',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'create',
                        'magazineId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'show',
                        'id' => '1',
                        'magazineId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'magazines/1/ads/1/edit',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'edit',
                        'id' => '1',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'magazines/ads/edit',
                        'action' => 'index',
                        'adId' => '1',
                        'magazineId' => '1'
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'magazineId' => '1',
                        'adId' => '1'
                    )
                )
            ),
            array(
                'PATCH',
                'magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1'
                    )
                )
            ),
            array(
                'PUT',
                'magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1'
                    )
                )
            ),
            array(
                'DELETE',
                'magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'magazines/ads',
                        'action' => 'destroy',
                        'id' => '1',
                        'magazineId' => '1'
                    )
                )
            ),

            // Nested, 3 levels (not recommended)
            array(
                'GET',
                'publishers/1/magazines/1/ads',
                array(
                    array(
                        'controller' => 'publishers/magazines',
                        'action' => 'ads',
                        'id' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'index',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'index',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'publishers/1/magazines/1/ads/new',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'show',
                        'id' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'new',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'POST',
                'publishers/1/magazines/1/ads',
                array(
                    array(
                        'controller' => 'publishers/magazines',
                        'action' => 'ads',
                        'id' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'create',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'ads',
                        'action' => 'create',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'publishers/1/magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'show',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'publishers/1/magazines/1/ads/1/edit',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'edit',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'publishers/magazines/ads/edit',
                        'action' => 'index',
                        'adId' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'magazineId' => '1',
                        'adId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'PATCH',
                'publishers/1/magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'PUT',
                'publishers/1/magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'update',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),
            array(
                'DELETE',
                'publishers/1/magazines/1/ads/1',
                array(
                    array(
                        'controller' => 'publishers/magazines/ads',
                        'action' => 'destroy',
                        'id' => '1',
                        'magazineId' => '1',
                        'publisherId' => '1'
                    )
                )
            ),

            // Format
            array(
                'GET',
                'photos.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'index',
                        '_format' => 'html',
                    )
                )
            ),
            array(
                'GET',
                'photos/new.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'new',
                        '_format' => 'html',
                    ),
                    array(
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => 'new',
                        '_format' => 'html',
                    ),
                )
            ),
            array(
                'POST',
                'photos.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'create',
                        '_format' => 'html',
                    )
                )
            ),
            array(
                'GET',
                'photos/1.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'show',
                        'id' => '1',
                        '_format' => 'html',
                    ),
                )
            ),
            array(
                'GET',
                'photos/1/edit.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1',
                        '_format' => 'html',
                    ),
                    array(
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'html',
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'html',
                    )
                )
            ),
            array(
                'PATCH',
                'photos/1.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                        '_format' => 'html',
                    )
                )
            ),
            array(
                'PUT',
                'photos/1.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'update',
                        'id' => '1',
                        '_format' => 'html',
                    )
                )
            ),
            array(
                'DELETE',
                'photos/1.html',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'destroy',
                        'id' => '1',
                        '_format' => 'html',
                    )
                )
            ),

            // JSON format
            array(
                'GET',
                'photos/1/edit.json',
                array(
                    array(
                        'controller' => 'photos',
                        'action' => 'edit',
                        'id' => '1',
                        '_format' => 'json',
                    ),
                    array(
                        'controller' => 'photos/edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'json',
                    ),
                    array(
                        'controller' => 'edit',
                        'action' => 'index',
                        'photoId' => '1',
                        '_format' => 'json',
                    )
                )
            ),

            // 复数转换
            array(
                'DELETE',
                'lotteries/1/histories/1',
                array(
                    array(
                        'controller' => 'lotteries/histories',
                        'action' => 'destroy',
                        'id' => '1',
                        'lotteryId' => '1',
                    )
                )
            ),

            // 只有命名空间
            array(
                'GET',
                'admin',
                array(
                    array(
                        'controller' => 'admin',
                        'action' => 'index'
                    )
                )
            ),

            // 根路径
            array(
                'GET',
                '/',
                array(
                    array(
                        'controller' => 'index',
                        'action' => 'index'
                    )
                )
            ),

            // 不支持的method
            array(
                'ABC',
                '/',
                array(
                    array(
                        'controller' => 'index',
                        'action' => 'index'
                    )
                )
            ),

            // 范围
            array(
                'GET',
                'user/cards/1',
                array(
                    array(
                        'controller' => 'user/cards',
                        'action' => 'show',
                        'id' => '1'
                    )
                )
            ),
            array(
                'GET',
                'user/cards/1/edit',
                array(
                    array(
                        'controller' => 'user/cards',
                        'action' => 'edit',
                        'id' => '1'
                    ),
                    array(
                        'controller' => 'user/cards/edit',
                        'action' => 'index',
                        'cardId' => '1'
                    ),
                    array(
                        'controller' => 'user/edit',
                        'action' => 'index',
                        'cardId' => '1'
                    )
                )
            ),
            array(
                'GET',
                'user/emails', // 当前用户的邮件地址 https://developer.github.com/v3/users/emails/
                array(
                    array(
                        'controller' => 'user/emails',
                        'action' => 'index'
                    )
                )
            ),
            array(
                'GET',
                'user/products/1/transfers/add',
                array(
                    array(
                        'controller' => 'user/products/transfers',
                        'action' => 'add',
                        'productId' => '1'
                    ),
                    array(
                        'controller' => 'user/products/transfers',
                        'action' => 'show',
                        'id' => 'add',
                        'productId' => '1'
                    ),
                    array(
                        'controller' => 'user/transfers',
                        'action' => 'add',
                        'productId' => '1'
                    )
                )
            ),

            // 连接符,下划线转换为驼峰
            array(
                'GET',
                'comic-books/new',
                array(
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'new'
                    ),
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'show',
                        'id' => 'new'
                    )
                )
            ),
            array(
                'GET',
                'comic_books/new',
                array(
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'new'
                    ),
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'show',
                        'id' => 'new'
                    )
                )
            ),
            array(
                'GET',
                'users/1/comic-books/new',
                array(
                    array(
                        'controller' => 'users/comicBooks',
                        'action' => 'new',
                        'userId' => '1'
                    ),
                    array(
                        'controller' => 'users/comicBooks',
                        'action' => 'show',
                        'id' => 'new',
                        'userId' => '1'
                    ),
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'new',
                        'userId' => '1'
                    ),
                )
            ),
            array(
                'GET',
                'users/1/comic-books/sync-with-server',
                array(
                    array(
                        'controller' => 'users/comicBooks',
                        'action' => 'syncWithServer',
                        'userId' => '1'
                    ),
                    array(
                        'controller' => 'users/comicBooks',
                        'action' => 'show',
                        'id' => 'sync-with-server',
                        'userId' => '1'
                    ),
                    array(
                        'controller' => 'comicBooks',
                        'action' => 'syncWithServer',
                        'userId' => '1'
                    )
                )
            ),

            // 组合资源
            array(
                'GET',
                'issues/comments',
                array(
                    array(
                        'controller' => 'issues/comments',
                        'action' => 'index'
                    )
                ),
                array(
                    'combinedResources' => array('issues/comments')
                )
            ),
            array(
                'GET',
                'issues/comments/1',
                array(
                    array(
                        'controller' => 'issues/comments',
                        'action' => 'show',
                        'id' => '1'
                    )
                ),
                array(
                    'combinedResources' => array('issues/comments')
                )
            ),
            array(
                'PUT',
                'issues/comments/1',
                array(
                    array(
                        'controller' => 'issues/comments',
                        'action' => 'update',
                        'id' => '1'
                    )
                ),
                array(
                    'combinedResources' => array('issues/comments')
                )
            ),
            array(
                'GET',
                'users/twinh/repos/wei/issues/comments/1',
                array(
                    array(
                        'controller' => 'users/repos/issues/comments',
                        'action' => 'show',
                        'id' => '1',
                        'userId' => 'twinh',
                        'repoId' => 'wei',
                    )
                ),
                array(
                    'combinedResources' => array('issues/comments')
                )
            ),
            // Combined resources with format
            array(
                'GET',
                'issues/comments.json',
                array(
                    array(
                        'controller' => 'issues/comments',
                        'action' => 'index',
                        '_format' => 'json',
                    )
                ),
                array(
                    'combinedResources' => array('issues/comments')
                )
            ),
        );
    }

    /**
     * @link http://zh.wiktionary.org/zh/%E9%99%84%E5%BD%95:%E8%8B%B1%E8%AF%AD%E4%B8%8D%E8%A7%84%E5%88%99%E5%A4%8D%E6%95%B0
     * @link https://github.com/doctrine/inflector/blob/master/tests/Doctrine/Tests/Common/Inflector/InflectorTest.php
     */
    public function dataForSingularize()
    {
        return array(
            array('life', 'lives'),

            array('man', 'men'),

            array('child', 'children'),

            array('auto', 'autos'),
            array('memo', 'memos'),
            array('photo', 'photos'),
            array('piano', 'pianos'),
            array('pro', 'pros'),
            array('solo', 'solos'),
            array('studio', 'studios'),
            array('tattoo', 'tattoos'),
            array('video', 'videos'),
            array('zoo', 'zoos'),

            array('echo', 'echoes'),
            array('hero', 'heroes'),
            array('potato', 'potatoes'),
            array('tomato', 'tomatoes'),

            array('zero', 'zeros'),
            array('zero', 'zeroes'),

            array('deer', 'deer'),
            array('fish', 'fish'),
            array('sheep', 'sheep'),

            array('formula', 'formulas'),

            array('datum', 'data'),
            array('analysis', 'analyses'),
            array('money', 'monies'),
            array('move', 'moves'),
            array('sex', 'sexes'),
            array('human', 'humans'),

            array('appendix', 'appendixes'),
            array('index', 'indexes'),
            array('matrix', 'matrixes'),

            array('history', 'histories'),
            array('information', 'information'),

            array('categoria', 'categorias'),
            array('house', 'houses'),
            array('bus', 'buses'),
            array('menu', 'menus'),
            array('news', 'news'),
            array('quiz', 'quizzes'),
            array('matrix_row', 'matrix_rows'),
            array('matrix', 'matrices'),
            array('alias', 'aliases'),
            array('Media', 'Media'),
            array('person', 'people'),
            array('glove', 'gloves'),
            array('wave', 'waves'),
            array('cafe', 'cafes'),
            array('roof', 'roofs'),
            array('cookie', 'cookies'),
            array('identity', 'identities'),
            array('criterion', 'criteria'),
            array('', ''),
        );
    }

    /**
     * @dataProvider dataForSingularize
     */
    public function testSingularize($singular, $plural)
    {
        $router = wei()->router;
        $method = new \ReflectionMethod($router, 'singularize');
        $method->setAccessible(true);
        $this->assertEquals($singular, $method->invoke($router, $plural));
    }
}
