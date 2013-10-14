<?php

namespace WidgetTest;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForUrl
     */
    public function testUrl($result, $url, $params = array())
    {
        $wei = $this->widget;
        $wei->request->setBaseUrl('/');
        $wei->router->setRoutes(array(
            '/<controller>/<action>',
            '/<controller>'
        ));
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
                '?id=twin',
                '',
                array('id' => 'twin')
            )
        );
    }
}
