<?php

namespace WidgetTest;

class EventTest extends TestCase
{
    /**
     * @var \Widget\Event
     */
    protected $object;
    
    protected $callback;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = Qwin::getInstance()->event;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    

    public function testGeter()
    {
        $that = $this;
        
        $this->off('test')
            ->on('test.ns.ns2', function(\Widget\Event $event) use($that) {
                $that->assertEquals('test', $event->getType());
                $this->assertEquals('ns.ns2', $event->getNamespace());
                $this->assertEquals(array('ns', 'ns2'), $event->getNamespaces());
            })
            ->trigger('test.ns.ns2');
            
        
    }
    
    public function testAddHandler()
    {
        $this->on('test', function(){});
        
        $this->assertTrue($this->eventManager->has('test'));
        
        $this->setExpectedException('\Widget\Exception', 'Parameter 2 should be a valid callback');
        
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
        
        $event = $this->event('test');
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
        $init();
    }

    public function testGetterAndSetterInEvent()
    {
        $event = $this->event('test', array('ns1', 'ns2'));
        
        $this->assertEquals('ns1.ns2', $event->getNamespace());
        
        $this->assertEquals(false, $event->isDefaultPrevented());
        
        $this->assertEquals(true, $event->preventDefault()->isDefaultPrevented());
        
        $this->assertEquals(array(), $event->getData());
        
        $this->assertEquals(time(), (int)$event->getTimeStamp());
    }
}
