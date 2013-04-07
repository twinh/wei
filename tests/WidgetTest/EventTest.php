<?php

namespace WidgetTest;

class EventTest extends TestCase
{
    /**
     * @var \Widget\Event
     */
    protected $object;

    protected $callback;

    public function testGeter()
    {
        $that = $this;

        $this->off('test')
            ->on('test.ns.ns2', function(\Widget\Event\EventInterface $event) use($that) {
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
        $this->on('test', function(){});

        $this->assertTrue($this->eventManager->has('test'));
    }
    
    /**
     * @expectedException \Widget\Exception\UnexpectedTypeException
     */
    public function testAddInvalidHandler()
    {
        $this->on('test', 'not callback');
    }

    public function testTriggerHandler()
    {
        $event = $this->off('test')
            ->on('test', function(){
                return false;
            })
            ->trigger('test');
        $this->assertEquals(false, $event->getResult());

        $event = $this->eventManager->create('test');
        $this->off('test')
            ->on('test', function(){
                return 'result';
            })
            ->trigger($event);
        $this->assertEquals('result', $event->getResult());

        $event = $this->off('test')
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

        $fixture = new \WidgetTest\Fixtures\WidgetWithCallbackEvent(array(
            'widget' => $this->widget,
            'callback' => function(){
                return false;
            }
        ));
        $event = $this->trigger('callback', array(), $fixture);
        $this->assertEquals(false, $event->getResult());
    }


    public function testHasHandler()
    {
        $fn = function(){};
        $em = $this->eventManager;

        $this->off('test')
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
        $em = $this->eventManager;

        $init = function() use($that) {
            $fn = function(){};
            $that->off('test')
                ->on('test', $fn)
                ->on('test.ns1', $fn)
                ->on('test.ns2', $fn)
                ->on('test.ns1.ns2', $fn);
        };

        $init();
        $this->assertTrue($em->has('test'));
        $this->off('test');
        $this->assertFalse($em->has('test'));
        
        $init();
        $this->assertTrue($em->has('test.ns1'));
        $this->off('test.ns1');
        $this->assertFalse($em->has('test.ns1'));
        
        $init();
        $this->assertTrue($em->has('test.ns1.ns2'));
        $this->off('test.ns1.ns2');
        $this->assertFalse($em->has('test.ns1.ns2'));
        
        $init();
        $this->assertTrue($em->has('.ns1'));
        $this->off('.ns1');
        $this->assertFalse($em->has('test.ns1'));
        $this->assertFalse($em->has('test.ns1.ns2'));
    }

    public function testGetterAndSetterInEvent()
    {
        $event = $this->eventManager->create('test.ns1.ns2');

        $this->assertEquals('ns1.ns2', $event->getNamespace());

        $this->assertEquals(false, $event->isDefaultPrevented());

        $this->assertEquals(true, $event->preventDefault()->isDefaultPrevented());

        $this->assertEquals(array(), $event->getData());

        $this->assertInternalType('float', $event->getTimeStamp());
    }
    
    public function testArrayAsOnParameters()
    {
        $this->off('test')
            ->on(array(
                'test.ns1' => function(){},
                'test.ns2' => function(){}
            ));
            
        $this->assertTrue($this->eventManager->has('test'));
        $this->assertTrue($this->eventManager->has('test.ns1'));
        $this->assertTrue($this->eventManager->has('test.ns2'));
    }
    
    public function testGetFullType()
    {
        $event = $this->eventManager->create('test.ns1');
        
        $this->assertEquals('test', $event->getType());
        
        $this->assertEquals('test.ns1', $event->getType(true));
    }
}
