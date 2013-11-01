<?php

use WeiTest\TestCase;

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
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'));
    }

    public function testSet() 
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__, array('expires' => 1));

        $this->assertEquals(__METHOD__, $wei->get('test'));

        $wei->set('test', __METHOD__, array('expires' => -1));

        $this->assertEquals(null, $wei->get('test'), 'test expiresd cookie');
    }

    public function testRemove() 
    {
        $wei = $this->object;

        $wei->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $wei->get('test'));

        $wei->offsetUnset('test');

        $this->assertEquals(null, $wei->get('test'));
    }
}
