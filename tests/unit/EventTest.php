<?php

namespace WeiTest;

class EventTest extends TestCase
{
    /**
     * @var \Wei\Event
     */
    protected $object;

    protected $callback;

    public function testGetter()
    {
        $that = $this;
        $event = $this->object;

        $event->off('test')
            ->on('test.ns.ns2', function(\Wei\Event $event) use($that) {
                $that->assertEquals('test', $event->getType());
                $that->assertEquals('ns.ns2', $event->getNamespace());
                $that->assertEquals(array('ns', 'ns2'), $event->getNamespaces());
            })
            ->trigger('test.ns.ns2');
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
            ->on('test', function($event){
                $event->stopPropagation();
                return 'first';
            })
            // won't be triggered
            ->on('test', function(){
                return 'second';
            })
            ->trigger('test');
        $this->assertEquals('first', $event->getResult());

        $this->fn = function(){
            return false;
        };
    }


    public function testHasHandler()
    {
        $fn = function(){};
        $em = $this->object;

        $this->object->off('test')
            ->on('test', $fn)
            ->on('test.ns1', $fn)
            ->on('test.ns2', $fn)
            ->on('test.ns1.ns2', $fn);

        $this->assertTrue($em->has('test'));

        $this->assertTrue($em->has('.ns1'));

        $this->assertTrue($em->has('test.ns1'));

        $this->assertTrue($em->has('.ns1.ns2'));

        $this->assertFalse($em->has('test2'));

        $this->assertFalse($em->has('.ns3'));

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
                ->on('test.ns1', $fn)
                ->on('test.ns2', $fn)
                ->on('test.ns1.ns2', $fn);
        };

        $init();
        $this->assertTrue($em->has('test'));
        $this->object->off('test');
        $this->assertFalse($em->has('test'));

        $init();
        $this->assertTrue($em->has('test.ns1'));
        $this->object->off('test.ns1');
        $this->assertFalse($em->has('test.ns1'));

        $init();
        $this->assertTrue($em->has('test.ns1.ns2'));
        $this->object->off('test.ns1.ns2');
        $this->assertFalse($em->has('test.ns1.ns2'));

        $init();
        $this->assertTrue($em->has('.ns1'));
        $this->object->off('.ns1');
        $this->assertFalse($em->has('test.ns1'));
        $this->assertFalse($em->has('test.ns1.ns2'));
    }

    public function testGetterAndSetterInEvent()
    {
        $event = $this->object('test.ns1.ns2');

        $this->assertEquals('ns1.ns2', $event->getNamespace());

        $this->assertEquals(false, $event->isDefaultPrevented());

        $this->assertEquals(true, $event->preventDefault()->isDefaultPrevented());

        $this->assertEquals(array(), $event->getData());

        $this->assertInternalType('float', $event->getTimeStamp());
    }

    public function testArrayAsOnParameters()
    {
        $this->object->off('test')
            ->on(array(
                'test.ns1' => function(){},
                'test.ns2' => function(){}
            ));

        $this->assertTrue($this->object->has('test'));
        $this->assertTrue($this->object->has('test.ns1'));
        $this->assertTrue($this->object->has('test.ns2'));
    }

    public function testGetFullType()
    {
        $event = $this->object('test.ns1');

        $this->assertEquals('test', $event->getType());

        $this->assertEquals('test.ns1', $event->getType(true));
    }
}
