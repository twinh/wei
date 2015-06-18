<?php

namespace WeiTest;

use Wei\Event;

class EventTest extends TestCase
{
    /**
     * @var \Wei\Event
     */
    protected $object;

    public function testEvent()
    {
        $event = $this->object;

        $results = $event->off('test')
            ->on('test', function() {
                return 'a';
            })
            ->on('test', function () {
                return 'b';
            })
            ->trigger('test');

        $this->assertEquals(array('a', 'b'), $results);
    }

    /**
     * @expectedException
     */
    public function testAddHandler()
    {
        $this->object->on('test', function(){});

        $this->assertTrue($this->object->has('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidHandler()
    {
        $this->object->on('test', 'not callback');
    }

    public function testTriggerHandler()
    {
        $results = $event = $this->object->off('test')
            ->on('test', function(){
                return false;
            })
            ->trigger('test');
        $this->assertEquals(false, $results[0]);

        $results = $this->object->off('test')
            ->on('test', function(){
                return false;
            })
            // won't be triggered
            ->on('test', function(){
                return 'second';
            })
            ->trigger('test');
        $this->assertEquals(array(false), $results);
    }


    public function testHasHandler()
    {
        $fn = function(){};
        $em = $this->object;

        $this->object->off('test')
            ->off('test.before')
            ->on('test', $fn)
            ->on('test.before', $fn);

        $this->assertTrue($em->has('test'));

        $this->assertTrue($em->has('test.before'));

        $this->assertFalse($em->has('test2'));

        $this->assertFalse($em->has('test2.ns3'));
    }


    public function testRemoveHandler()
    {
        $that = $this;
        $em = $this->object;

        $init = function() use($that) {
            $fn = function(){};
            $that->event->off('test')
                ->on('test', $fn)
                ->on('test.before', $fn)
                ->on('test.after', $fn)
                ->on('test.before.ns2', $fn);
        };

        $init();
        $this->assertTrue($em->has('test'));
        $this->object->off('test');
        $this->assertFalse($em->has('test'));

        $init();
        $this->assertTrue($em->has('test.before'));
        $this->object->off('test.before');
        $this->assertFalse($em->has('test.before'));

        $init();
        $this->assertTrue($em->has('test.before.ns2'));
        $this->object->off('test.before.ns2');
        $this->assertFalse($em->has('test.before.ns2'));
    }

    public function testArrayAsOnParameters()
    {
        $this->object->off('test')
            ->on(array(
                'test.before' => function(){},
                'test.after' => function(){}
            ));

        $this->assertTrue($this->object->has('test.before'));
        $this->assertTrue($this->object->has('test.after'));
    }

    public function testArrayArgs()
    {
        $this->object->on('test', function ($arg) {
            return $arg;
        });

        $result = $this->object->trigger('test', array('test'));

        $this->assertEquals(array('test'), $result);
    }

    public function testNotArrayArgs()
    {
        $this->object->on('test', function ($arg) {
            return $arg;
        });

        $result = $this->object->trigger('test', 'test');

        $this->assertEquals(array('test'), $result);
    }
}
