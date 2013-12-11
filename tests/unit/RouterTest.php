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

    public function testUriForStaticRule()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user/login',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('user/login?var1=value1', $router->generatePath(array(
            'controller' => 'user',
            'action' => 'login',
            'var1' => 'value1',
        )));
    }

    public function testUriForNotMatchStaticRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'pattern' => 'user/login',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('?controller=user&var1=value1', $router->generatePath(array(
            'controller' => 'user',
            'var1' => 'value1',
        )));
    }

    public function testUriWithoutRule()
    {
        $router = $this->object;

        $router->remove('default');

        $this->assertEquals('?var1=value1&var2=value2', $router->generatePath(array(
            'var1' => 'value1',
            'var2' => 'value2',
        )));
    }

    public function testUriForRequiredRule()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog/<page>',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('blog/1', $router->generatePath(array(
            'matched' => true,
            'page' => 1,
        )));
    }

    public function testUriForRequiredRuleAndRequiredParameterNotPassed()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog/<page>',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('?matched=1', $router->generatePath(array(
            'matched' => true,
        )));
    }

    public function testUriForRequiredRuleAndParameterNotMatchRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'pattern' => 'blog/<page>',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('?matched=1&page=notDigits', $router->generatePath(array(
            'matched' => true,
            'page' => 'notDigits',
        )));
    }

    public function testUriForOptionalRule()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog(/<page>)',
            'defaults' => array(
                'controller' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog', $router->generatePath(array(
            'controller' => 'blog',
        )));
    }

    public function testUriForOptionalRuleAndParameterNotMatchRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'pattern' => 'blog(/<page>)',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'controller' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('?controller=blog&page=notDigits', $router->generatePath(array(
            'controller' => 'blog',
            'page' => 'notDigits'
        )));
    }

    public function testUriWithNameParameter()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog(/<page>)',
            'defaults' => array(
                'controller' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog/2', $router->generatePath(array(
            'controller' => 'blog',
            'page' => '2',
        ), 'blogList'));
    }

    public function testNoRuleMatchd()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'blog/<page>'
        ));

        $this->assertEquals('?controller=blog', $router->generatePath(array(
            'controller' => 'blog'
        )));
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

    public function testPatternIsOptional()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => '(<controller>(/<action>))',
            'defaults' => array(
                'controller' => 'index',
                'action' => 'index'
            ),
        ));

        $this->assertEquals('?key=value', $router->generatePath(array(
            'key' => 'value'
        )));
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

    public function testRestRouter()
    {
        $router = $this->object;

        $router->get('/', function(){
            return 'index';
        });

        $router->get('/docs', function(){
            return 'docs';
        });

        $router->get('/docs/<name>', function($wei, $name){
            return $name;
        });

        $router->get('/feedback', function(){
            return 'write please';
        });

        $router->post('/feedback', function(){
            return 'thanks';
        });

        $router->put('/article', function(){
           return 'put';
        });

        $router->delete('/article', function(){
            return 'delete';
        });

        $router->request('/page', function(){
            return 'page';
        });

        $this->assertContains('index', $this->getRouterOutput('/'));

        $this->assertContains('docs', $this->getRouterOutput('/docs'));

        $this->assertContains('test-of-router', $this->getRouterOutput('/docs/test-of-router'));

        $this->assertContains('write please', $this->getRouterOutput('/feedback'));

        $this->assertContains('write please', $this->getRouterOutput('/feedback', 'GET'));

        $this->assertContains('thanks', $this->getRouterOutput('/feedback', 'POST'));

        $this->assertContains('put', $this->getRouterOutput('/article', 'PUT'));

        $this->assertContains('delete', $this->getRouterOutput('/article', 'DELETE|POST'));

        foreach (array('GET', 'POST', 'PUT', 'DELETE') as $method) {
            $this->assertContains('page', $this->getRouterOutput('/page', $method));
        }
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNotFoundException()
    {
        $router = $this->object;

        $router->request('/page', function(){});

        $router('/pages');
    }

    public function testPathInfoAndMethodFromRequest()
    {
        $router = $this->object;

        $this->request->setPathInfo('/');
        $this->request->setMethod('GET');

        $router->get('/', function(){
            return 'index';
        });

        $this->assertContains('index', $this->getRouterOutput());
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
        $this->assertEquals('/admin/posts/edit/234', $router->generatePath($params));

        $params = array(
            'controller' => 'admin/posts',
            'action' => 'edit',
            'id' => '234',
            'format' => 'html'
        );
        $this->assertIsSubset($params, $router->match('/admin/posts/edit/234.html'));
        $this->assertEquals('/admin/posts/edit/234.html', $router->generatePath($params));

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
        $this->assertEquals($path, $router->generatePath($params));

        $params = array(
            'controller' => 'posts',
            'action' => 'index',
        );
        $path = '/posts/index';
        $this->assertIsSubset($params, $router->match($path));
        $this->assertEquals($path, $router->generatePath($params));

        $params = array(
            'controller' => 'posts',
        );
        $path = '/posts';
        $this->assertIsSubset($params, $router->match($path));
        $this->assertEquals($path, $router->generatePath($params));

        $this->assertIsSubset(array(
            'controller' => null, // $this->request('controller', 'index'); => 'index'
            'action' => null,
            'id' => null
        ), $router->match('/'));
        $this->assertEquals('/', $router->generatePath(array()));
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
}
