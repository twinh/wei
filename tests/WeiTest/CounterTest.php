<?php

namespace WeiTest;

class CounterTest extends TestCase
{
    /**
     * @var \Wei\Counter
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();
        $this->object->remove('test');
    }

    public function testIncr()
    {
        $result = $this->object->incr('test');
        $this->assertEquals(1, $result);

        $result = $this->object->incr('test', 2);
        $this->assertEquals(3, $result);
    }

    public function testDecr()
    {
        $this->object->set('test', 10);

        $result = $this->object->decr('test');
        $this->assertEquals(9, $result);

        $result = $this->object->decr('test', 2);
        $this->assertEquals(7, $result);
    }

    public function testSetAndGet()
    {
        $result = $this->object->set('test', 10);
        $this->assertTrue($result);
        $this->assertEquals(10, $this->object->get('test'));
    }

    public function testRemove()
    {
        $result = $this->object->remove('test');
        $this->assertFalse($result);

        $result = $this->object->set('test', 10);
        $this->assertTrue($result);

        $result = $this->object->remove('test');
        $this->assertTrue($result);
    }

    public function testExists()
    {
        $result = $this->object->exits('test');
        $this->assertFalse($result);

        $result = $this->object->set('test', 10);
        $this->assertTrue($result);

        $result = $this->object->exits('test');
        $this->assertTrue($result);
    }
}