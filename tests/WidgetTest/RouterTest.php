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
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals($router->match('user/login'), array(
            '_route' => 'test',
            'module' => 'user',
            'action' => 'login'
        ));
    }

    public function testMatchRequiredRoute()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user/<action>',
            'defaults' => array(
                'module' => 'user',
            ),
        ));

        $this->assertEquals(array(
            '_route' => 0,
            'module' => 'user',
            'action' => 'login',
        ), $router->match('user/login'));
    }

    public function testMatchOptionalRoute()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user(/<page>)',
            'defaults' => array(
                'module' => 'user',
                'page' => '1'
            ),
        ));

        $this->assertEquals(array(
            '_route' => 0,
            'module' => 'user',
            'page' => '1',
        ), $router->match('user'));

        $this->assertEquals(array(
            '_route' => 0,
            'module' => 'user',
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

    public function testMatchUriWithRules()
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
            'pattern' => '<module>(/<action>)'
        ));

        $this->assertEquals(array(
            '_route' => 'default',
            'module' => 'blog',
            'action' => 'list'
        ), $router->match('blog/list', null, 'default'));
    }

    public function testUriForStaticRule()
    {
        $router = $this->object;

        $router->set(array(
            'pattern' => 'user/login',
            'defaults' => array(
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('user/login?var1=value1', $router->path(array(
            'module' => 'user',
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
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('?module=user&var1=value1', $router->path(array(
            'module' => 'user',
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
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog', $router->generatePath(array(
            'module' => 'blog',
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
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('?module=blog&page=notDigits', $router->generatePath(array(
            'module' => 'blog',
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
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog/2', $router->generatePath(array(
            'module' => 'blog',
            'page' => '2',
        ), 'blogList'));
    }
}
