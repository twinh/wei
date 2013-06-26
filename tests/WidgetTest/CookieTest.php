<?php

use WidgetTest\TestCase;

class CookieTest extends TestCase
{
    public function testInvoker() 
    {
        $cookie = $this->object;

        $cookie('test', __METHOD__);

        $this->assertEquals(__METHOD__, $cookie('test'));
    }
    
    public function testGet() 
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $widget->get('test'));
    }

    public function testSet() 
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__, array('expires' => 1));

        $this->assertEquals(__METHOD__, $widget->get('test'));

        $widget->set('test', __METHOD__, array('expires' => -1));

        $this->assertEquals(null, $widget->get('test'), 'test expiresd cookie');
    }

    public function testRemove() 
    {
        $widget = $this->object;

        $widget->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $widget->get('test'));

        $widget->offsetUnset('test');

        $this->assertEquals(null, $widget->get('test'));
    }
}
