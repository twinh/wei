<?php

namespace WeiTest;

use Wei\Event;

class EventTest extends TestCase
{
    /**
     * @var \Wei\Event
     */
    protected $object;

    public function testGetter()
    {
        $that = $this;
        $event = $this->object;

        $event->off('test')
            ->on('test', function(\Wei\Event $event) use($that) {
                $that->assertEquals('test', $event->getName());
            })
            ->trigger('test');
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
        $event = $this->object->off('test')
            ->on('test', function(){
                return false;
            })
            ->trigger('test');
        $this->assertEquals(false, $event->getResult());

        /** @var \Wei\Event $event */
        $event = $this->object('test');
        $this->object->off('test')
            ->on('test', function(){
                return 'result';
            })
            ->trigger($event);
        $this->assertEquals('result', $event->getResult());

        $event = $this->object->off('test')
            ->on('test', function(Event $event){
                return false;
            })
            // won't be triggered
            ->on('test', function(){
                return 'second';
            })
            ->trigger('test');
        $this->assertEquals(false, $event->getResult());

        $this->fn = function(){
            return false;
        };
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

    public function testGetterAndSetterInEvent()
    {
        $event = $this->object('test.before');

        $this->assertEquals(array(), $event->getData());

        $this->assertInternalType('float', $event->getTimeStamp());
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
}
