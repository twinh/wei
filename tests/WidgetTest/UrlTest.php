<?php

namespace WidgetTest;

class UrlTest extends TestCase
{
    /**
     * @dataProvider providerForUrl
     */
    public function testUrl($url)
    {
        $this->request->setBaseUrl('/');

        $args = func_get_args();
        array_pop($args);
        $this->assertEquals($url, call_user_func_array(array($this, 'url'), $args));
    }

    public function providerForUrl()
    {
        return array(
            array(
                'users?id=twin',
                'users?id=twin'
            ),
            array(
                'user?id=twin',
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
