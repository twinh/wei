<?php

namespace WidgetTest;

class RouterTest extends TestCase
{
    public function testAddRouteWithName()
    {
        $router = $this->object;

        $router->set(array(
            'name' => __FUNCTION__,
            'pattern' => 'blog(/<page>)',
        ));

        $this->assertNotNull($router->getRoute(__FUNCTION__));
    }

    public function testMatchStaticRoute()
    {
        $router = $this->object;

        $router->set(array(
            'name' => 'test',
            'pattern' => 'user/login',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals($router->match('user/login'), array(
            '_route' => 'test',
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

        $this->assertEquals(array(
            '_route' => 0,
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

        $this->assertEquals(array(
            '_route' => 0,
            'controller' => 'user',
            'page' => '1',
        ), $router->match('user'));

        $this->assertEquals(array(
            '_route' => 0,
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

        $this->assertEquals(array(
            '_route' => 0,
            'matched' => true,
        ), $router->match('postOrPut'));

        $this->assertEquals(array(
            '_route' => 0,
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
            'pattern' => '/blog(/<page>)(<format>)',
            'defaults' => array(
                'controller' => 'blog',
                'page' => '1'
            ),
        ));
        
        $parameters = $router->match('/blog/123');
        
        $this->assertArrayHasKey('format', $parameters);
        
        $this->assertNull($parameters['format']);
        
        $this->assertEquals(array(
            '_route' => 0,
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

        $this->assertEquals(array(
            '_route' => 0,
            'matched' => true,
            'page' => '1',
        ), $router->match('blog/1'));
    }

    public function testMatchWithNameParameter()
    {
        $router = $this->object;
        
        $router->set(array(
            'name' => 'default',
            'pattern' => '<controller>(/<action>)'
        ));

        $this->assertEquals(array(
            '_route' => 'default',
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

        $this->assertEquals('user/login?var1=value1', $router->path(array(
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

        $this->assertEquals('?controller=user&var1=value1', $router->path(array(
            'controller' => 'user',
            'var1' => 'value1',
        )));
    }

    public function testUriWithoutRule()
    {
        $router = $this->object;

        $router->remove('default');

        $this->assertEquals('?var1=value1&var2=value2', $router->path(array(
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
            'name' => 'blogList',
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
            'name' => 'test',
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
            'name' => 'test',
            'pattern' => 'blog/<page>'
        ));
        
        $this->assertNotEquals(false, $router->getRoute('test'));
        
        $router->remove('test');
        
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
}
