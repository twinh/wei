<?php

use WeiTest\TestCase;

class CookieTest extends TestCase
{
    /**
     * @var \Wei\Cookie
     */
    protected $object;

    public function testInvoker()
    {
        $cookie = $this->object;

        $cookie('test', __METHOD__);

        $this->assertEquals(__METHOD__, $cookie('test'));
    }

    public function testGet()
    {
        $cookie = $this->object;

        $cookie->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $cookie->get('test'));
    }

    public function testSet()
    {
        $cookie = $this->object;

        $cookie->set('test', __METHOD__, array('expires' => 1));

        $this->assertEquals(__METHOD__, $cookie->get('test'));

        $cookie->set('test', __METHOD__, array('expires' => -1));

        $this->assertEquals(null, $cookie->get('test'), 'test expiresd cookie');
    }

    public function testRemove()
    {
        $cookie = $this->object;

        $cookie->set('test', __METHOD__);

        $this->assertEquals(__METHOD__, $cookie->get('test'));

        $cookie->offsetUnset('test');

        $this->assertEquals(null, $cookie->get('test'));
    }

    public function testClear()
    {
        $cookie = $this->object;

        $cookie->set('test', __METHOD__);

        $cookie->clear();

        $this->assertNull($cookie->get('test'));
    }

    public function testToArray()
    {
        $cookie = $this->object;

        $cookie->set('test', __METHOD__);

        $array = $cookie->toArray();

        $this->assertInternalType('array', $array);

        $this->assertArrayHasKey('test', $array);
    }
}
